

(function(){
    'use strict';

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addCustomerEmailModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addCustomerEmailModal"));
    const makePaymentModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#makePaymentModal"));

    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
    });
    document.getElementById('addCustomerEmailModal').addEventListener('hide.tw.modal', function(event) {
        $('#addCustomerEmailModal [name="customer_email"]').val('');
    });
    document.getElementById('makePaymentModal').addEventListener('hide.tw.modal', function(event) {
        $('#makePaymentModal [name="payment_date"]').val('');
        $('#makePaymentModal [name="payment_method_id"]').val('');
        $('#makePaymentModal [name="amount"]').val('');
    });

    $('#successModal .agreeWith').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        if($theBtn.attr('data-action') == 'RELOAD'){
            if($theBtn.attr('data-redirect') != ''){
                window.location.href = $theBtn.attr('data-redirect');
            }else{
                window.location.reload();
            }
        }else{
            successModal.hide();
        }
    });

    $('#recordForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('recordForm');
        let $theForm = $(this);
        let type = $theForm.find('[name="submit_type"]').val();
        let gsr_id = $theForm.find('#gsr_id').val();
        let form_slug = $theForm.find('#form_slug').val();

        $('.formSubmits', $theForm).attr('disabled', 'disabled');
        $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeIn();
        $('.formSubmits.submit_'+type, $theForm).addClass('active');

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('invoices.action'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('.formSubmits', $theForm).removeAttr('disabled');
            $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeOut();
            $('.formSubmits.submit_'+type, $theForm).removeClass('active');

            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    if(response.data.red){
                        window.location.href = response.data.red
                    }
                }, 1500);
            }
        }).catch(error => {
            $('.formSubmits', $theForm).removeAttr('disabled');
            $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeOut();
            $('.formSubmits.submit_'+type, $theForm).removeClass('active');
            if (error.response) {
                if (error.response.status == 422) {
                    warningModal.show();
                    document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                        $("#warningModal .warningModalTitle").html("Error Found!");
                        $("#warningModal .warningModalDesc").html(error.response.data.msg);
                    });

                    setTimeout(() => {
                        warningModal.hide();
                    }, 1500);
                } else {
                    console.log('error');
                }
            }
        });
    });

    $('.editRecordBtn').on('click', function(e){
        e.preventDefault();
        var $theBtn = $(this);
        var invoice_id = $theBtn.attr('data-id');

        $theBtn.addClass('active').attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();
        $theBtn.siblings('.action_btns').removeClass('active').attr('disabled', 'disabled');

        axios({
            method: "post",
            url: route('invoices.edit.ready'),
            data: {invoice_id : invoice_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                // console.log(response.data);
                // return false;

                let row = response.data.row;
                let form_id = response.data.form;
                
                localStorage.clear();

                localStorage.setItem('invoice_id', row.invoice_id);
                localStorage.setItem('invoice_number', JSON.stringify(row.invoice_number));
                localStorage.setItem('invoice', JSON.stringify(row.invoice));
                localStorage.setItem('job', JSON.stringify(row.job));
                localStorage.setItem('customer', JSON.stringify(row.customer));
                localStorage.setItem('job_address', JSON.stringify(row.job_address));

                
                if(row.invoiceNotes){
                    localStorage.setItem('invoiceNotes', JSON.stringify(row.invoiceNotes));
                }
                if(row.issued_date){
                    localStorage.setItem('issued_date', JSON.stringify(row.issued_date));
                }
                if(row.invoiceItems){
                    localStorage.setItem('invoiceItemsCount', row.invoiceItemsCount);
                    localStorage.setItem('invoiceItems', JSON.stringify(row.invoiceItems));
                }
                if(row.invoiceDiscounts){
                    localStorage.setItem('invoiceDiscounts', JSON.stringify(row.invoiceDiscounts));
                }
                if(row.invoiceAdvance){
                    localStorage.setItem('invoiceAdvance', JSON.stringify(row.invoiceAdvance));
                }
                if(row.invoiceExtra){
                    localStorage.setItem('invoiceExtra', JSON.stringify(row.invoiceExtra));
                }
                

                window.location.href = response.data.red
            }
        }).catch(error => {
            if (error.response) {
                console.log('error');
            }
        });
    });

    $('#addCustomerEmailForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('addCustomerEmailForm');
        let $theForm = $(this);
        let customer_email = $theForm.find('#customer_email').val();

        if(customer_email == ''){
            $('#sendMailBtn', $theForm).removeAttr('disabled');
            $('#sendMailBtn .theLoader', $theForm).fadeOut();

            $theForm.find('.acc__input-error.error-customer_email').fadeIn().html('This field is required.');
        }else{
            $theForm.find('.acc__input-error').fadeOut().html('');
            $('#sendMailBtn', $theForm).attr('disabled', 'disabled');
            $('#sendMailBtn .theLoader', $theForm).fadeIn();

            let form_data = new FormData(form);
            form_data.append('submit_type', 3);
            axios({
                method: "post",
                url: route('invoices.action'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#sendMailBtn', $theForm).removeAttr('disabled');
                $('#sendMailBtn .theLoader', $theForm).fadeOut();

                if (response.status == 200) {
                    addCustomerEmailModal.hide();

                    successModal.show();
                    document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                        $("#successModal .successModalTitle").html("Congratulations!");
                        $("#successModal .successModalDesc").html(response.data.msg);
                        $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                    });

                    setTimeout(() => {
                        successModal.hide();
                        if(response.data.red){
                            window.location.href = response.data.red
                        }
                    }, 1500);
                }
            }).catch(error => {
                $('#sendMailBtn', $theForm).removeAttr('disabled');
                $('#sendMailBtn .theLoader', $theForm).fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        warningModal.show();
                        document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                            $("#warningModal .warningModalTitle").html("Error Found!");
                            $("#warningModal .warningModalDesc").html(error.response.data.msg);
                        });

                        setTimeout(() => {
                            warningModal.hide();
                        }, 1500);
                    } else {
                        console.log('error');
                    }
                }
            });
        }
    });

    $(document).on('click', '#createInvoiceBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let record_id = $theBtn.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to create an invoice for this certificate? Click on agree to continue.');
            $('#confirmModal .agreeWith').attr('data-id', record_id);
            $('#confirmModal .agreeWith').attr('data-action', 'CREATEINVOICE');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'CREATEINVOICE'){
            axios({
                method: 'post',
                url: route('records.create.invoice'),
                data: {record_id : row_id},
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                        $("#successModal .successModalTitle").html("Congratulations!");
                        $("#successModal .successModalDesc").html(response.data.msg);
                        $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                    });

                    setTimeout(() => {
                        successModal.hide();
                        if(response.data.red){
                            window.location.href = response.data.red
                        }
                    }, 1500);
                }
            }).catch(error =>{
                console.log(error)
            });
        }
    })

    $('#makePaymentForm').on('input', '[name="amount"]', function(){
        let amount = $(this).val();
        let due = $('#makePaymentForm').find('[name="due_amount"]').val() * 1;
        if(amount.length > 0){
            if(amount > due){
                $('#makePaymentForm .acc__input-error.error-amount').html('Amount can not grater than the due amount.').fadeIn();
                $(this).val('');

                setTimeout(() => {
                    $('#makePaymentForm .acc__input-error.error-amount').html('').fadeOut();
                }, 2000);
            }else{
                $('#makePaymentForm .acc__input-error.error-amount').html('').fadeOut();
            }
        }else{
            $('#makePaymentForm .acc__input-error.error-amount').html('').fadeOut();
        }
    })


    $('#makePaymentForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('makePaymentForm');
        const $theForm = $(this);
        
        $('#makePaymentForm .acc__input-error').html('').fadeOut();
        $('#payBtn', $theForm).attr('disabled', 'disabled');
        $("#payBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('invoices.make.payment'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#payBtn', $theForm).removeAttr('disabled');
            $("#payBtn .theLoader").fadeOut();

            if (response.status == 200) {
                makePaymentModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html(response.data.title);
                    $("#successModal .successModalDesc").html(response.data.message);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.reload();
                }, 1500);
            }
        }).catch(error => {
            $('#payBtn', $theForm).removeAttr('disabled');
            $("#payBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#makePaymentForm .${key}`).addClass('border-danger');
                        $(`#makePaymentForm  .error-${key}`).html(val).fadeIn();
                    }
                } else if (error.response.status == 304) {
                    warningModal.show();
                    document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                        $("#warningModal .warningModalTitle").html("Error Found!");
                        $("#warningModal .warningModalDesc").html(error.response.data.message);
                    });

                    setTimeout(() => {
                        warningModal.hide();
                    }, 1500);
                } else {
                    console.log('error');
                }
            }
        });
    })
})()