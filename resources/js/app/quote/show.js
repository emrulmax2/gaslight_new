

(function(){
    'use strict';

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const addCustomerEmailModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#addCustomerEmailModal"));

    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
    });
    document.getElementById('addCustomerEmailModal').addEventListener('hide.tw.modal', function(event) {
        $('#addCustomerEmailModal [name="customer_email"]').val('');
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
            url: route('records.action'),
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
        var quote_id = $theBtn.attr('data-id');

        $theBtn.addClass('active').attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();
        $theBtn.siblings('.action_btns').removeClass('active').attr('disabled', 'disabled');

        axios({
            method: "post",
            url: route('quotes.edit.ready'),
            data: {quote_id : quote_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                // console.log(response.data);
                // return false;

                let row = response.data.row;
                let form_id = response.data.form;
                
                localStorage.clear();

                localStorage.setItem('quote_id', row.quote_id);
                localStorage.setItem('quote_number', JSON.stringify(row.quote_number));
                localStorage.setItem('quote', JSON.stringify(row.quote));
                localStorage.setItem('customer', JSON.stringify(row.customer));
                localStorage.setItem('billing_address', JSON.stringify(row.billing_address));

                if(row.job_address){
                    localStorage.setItem('job_address', JSON.stringify(row.job_address));
                }
                if(row.job){
                    localStorage.setItem('job', JSON.stringify(row.job));
                }

                if(row.quoteNotes){
                    localStorage.setItem('quoteNotes', JSON.stringify(row.quoteNotes));
                }
                if(row.issued_date){
                    localStorage.setItem('issued_date', JSON.stringify(row.issued_date));
                }
                if(row.quoteItems){
                    localStorage.setItem('quoteItemsCount', row.quoteItemsCount);
                    localStorage.setItem('quoteItems', JSON.stringify(row.quoteItems));
                }
                if(row.quoteDiscounts){
                    localStorage.setItem('quoteDiscounts', JSON.stringify(row.quoteDiscounts));
                }
                if(row.quoteExtra){
                    localStorage.setItem('quoteExtra', JSON.stringify(row.quoteExtra));
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
                url: route('quotes.action'),
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

    $(document).on('click', '#createQuoteBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let record_id = $theBtn.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to create an quote for this certificate? Click on agree to continue.');
            $('#confirmModal .agreeWith').attr('data-id', record_id);
            $('#confirmModal .agreeWith').attr('data-action', 'CREATEQUOTE');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'CREATEQUOTE'){
            axios({
                method: 'post',
                url: route('records.create.quote'),
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
})()