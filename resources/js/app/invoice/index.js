("use strict");

var invoiceListTable = (function () {
    var _tableGen = function () {
        let queryStr = $('#query').val() != '' ? $('#query').val() : '';
        let status = $(document).find('.singleStatus.active').attr('data-value') ? $(document).find('.singleStatus.active').attr('data-value') : 'Draft,Send';
        
        axios({
            method: 'get',
            url: route('invoices.list', {queryStr: queryStr, status: status}),
            data: {queryStr: queryStr},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                $('#invoiceListTable tbody').html(response.data.html);

                createIcons({
                    icons,
                    attrs: { "stroke-width": 1.5 },
                    nameAttr: "data-lucide",
                });
            }
        }).catch(error =>{
            console.log(error)
        });


    };
    return {
        init: function () {
            _tableGen();
        },
    };
})();

(function () {
    if ($("#invoiceListTable").length) {
        invoiceListTable.init();

        // On submit filter form
        $("#query").on("keypress", function (e) {
            var key = e.keyCode || e.which;
            if(key === 13){
                e.preventDefault();
    
                invoiceListTable.init();
                console.log('enter')
            }
        });

        $("#invoiceListTable").on('click', '.invoiceRow td:not(:last-child)', function(e){
            let $theCol = $(this);
            let $theRow = $theCol.closest('.invoiceRow')
            let theUrl = $theRow.attr('data-url');
            window.location.href = theUrl;
        });
        const statusDropdown = tailwind.Dropdown.getOrCreateInstance(document.querySelector("#statusDropdown"));

        $(document).on('click', '.singleStatus', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            let label = $theBtn.attr('data-label');

            $(document).find('.singleStatus').removeClass('active');
            $theBtn.addClass('active');
            $(document).find('.selectedStatusLabel').text(label);    
            
            invoiceListTable.init();
            statusDropdown.hide();
        });

        const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
        const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
        const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
        const cancelReasonModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#cancelReasonModal"));
        const invoiceConfirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#invoiceConfirmModal"));
        const makePaymentModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#makePaymentModal"));
        const makeRefundModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#makeRefundModal"));

        document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
            $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
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

        document.getElementById('cancelReasonModal').addEventListener('hide.tw.modal', function(event) {
            $('#cancelReasonModal [name="invoice_cancel_reason_id"]').prop('checked', false);
            $('#cancelReasonModal [name="cancel_reason_note"]').val('');
            $('#cancelReasonModal [name="invoice_id"]').val('0');
        });

        document.getElementById('invoiceConfirmModal').addEventListener('hide.tw.modal', function(event) {
            $('#invoiceConfirmModal [name="invoice_id"]').val('0');
        });

        document.getElementById('makePaymentModal').addEventListener('hide.tw.modal', function(event) {
            $('#makePaymentModal .acc__input-error').html('').fadeOut();
            $('#makePaymentModal [name="payment_date"]').val('');
            $('#makePaymentModal [name="payment_method_id"]').val('');
            $('#makePaymentModal [name="amount"]').val('');

            $('#makePaymentModal .totalDue').html('');
            $('#makePaymentModal [name="invoice_id"]').val('0');
            $('#makePaymentModal [name="total_amount"]').val('0');
            $('#makePaymentModal [name="due_amount"]').val('0');
        });

        document.getElementById('makeRefundModal').addEventListener('hide.tw.modal', function(event) {
            $('#makeRefundModal .acc__input-error').html('').fadeOut();
            $('#makeRefundModal [name="payment_date"]').val('');
            $('#makeRefundModal [name="payment_method_id"]').val('');
            $('#makeRefundModal [name="amount"]').val('');

            $('#makeRefundModal .totalDue').html('');
            $('#makeRefundModal [name="invoice_id"]').val('0');
            $('#makeRefundModal [name="total_amount"]').val('0');
            $('#makeRefundModal [name="paid_amount"]').val('0');
        });

        $(document).on('click', '.cancelInvoice', function(e){
            e.preventDefault();
            let $theLink = $(this);
            let invoice_id = $theLink.attr('data-id');

            cancelReasonModal.show();
            document.getElementById('cancelReasonModal').addEventListener('shown.tw.modal', function(event) {
                $('#cancelReasonModal [name="invoice_id"]').val(invoice_id);
            });
        });

        $('#cancelReasonForm').on('submit', function(e){
            e.preventDefault();
            const form = document.getElementById('cancelReasonForm');
            const $theForm = $(this);
            
            $('#cancelReasonForm .acc__input-error').html('').fadeOut();
            $('#updateRsnBtn', $theForm).attr('disabled', 'disabled');
            $("#updateRsnBtn .theLoader").fadeIn();

            let checkedReason = $('#cancelReasonForm [name="invoice_cancel_reason_id"]:checked').length;

            if(checkedReason == 0){
                $('#updateRsnBtn', $theForm).removeAttr('disabled');
                $("#updateRsnBtn .theLoader").fadeOut();
                $('#cancelReasonForm .acc__input-error.error-invoice_cancel_reason_id').html('Please select a reason').fadeIn();

                setTimeout(() => {
                    $('#cancelReasonForm .acc__input-error').html('').fadeOut();
                }, timeout);
            }else{
                let form_data = new FormData(form);
                axios({
                    method: "post",
                    url: route('invoices.cancel'),
                    data: form_data,
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    $('#updateRsnBtn', $theForm).removeAttr('disabled');
                    $("#updateRsnBtn .theLoader").fadeOut();

                    if (response.status == 200) {
                        cancelReasonModal.hide();

                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                        });

                        setTimeout(() => {
                            successModal.hide();
                            window.location.reload();
                        }, 1500);
                    }
                }).catch(error => {
                    $('#cancelReasonForm .acc__input-error').html('').fadeOut();
                    $('#saveStatusBtn', $theForm).removeAttr('disabled');
                    $("#saveStatusBtn .theLoader").fadeOut();
                    if (error.response) {
                        if (error.response.status == 422) {
                            for (const [key, val] of Object.entries(error.response.data.errors)) {
                                $(`#cancelReasonForm .${key}`).addClass('border-danger');
                                $(`#cancelReasonForm  .error-${key}`).html(val);
                            }
                        } else {
                            console.log('error');
                        }
                    }
                });
            }
        });

        $(document).on('click', '.paidInvoice', function(e){
            e.preventDefault();
            let $theLink = $(this);
            let invoice_id = $theLink.attr('data-id');
            let has_due = $theLink.attr('data-hasdue');

            if(has_due == 1){
                invoiceConfirmModal.show();
                document.getElementById('invoiceConfirmModal').addEventListener('shown.tw.modal', function(event) {
                    $('#invoiceConfirmModal [name="invoice_id"]').val(invoice_id);
                });
            }else{
                confirmModal.show();
                document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
                    $('#confirmModal .confirmModalTitle').html('Are you sure?');
                    $('#confirmModal .confirmModalDesc').html('Do you really want to update the status? Click on agree to continue.');
                    $('#confirmModal .agreeWith').attr('data-id', invoice_id);
                    $('#confirmModal .agreeWith').attr('data-status', 'Paid');
                    $('#confirmModal .agreeWith').attr('data-action', 'UPDATESTS');
                });
            }
        })

        $(document).on('click', '#makePayment', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            let $form = $theBtn.closest('#invoiceConfirmForm')
            let invoice_id = $form.find('[name="invoice_id"]').val();

            axios({
                method: 'post',
                url: route('invoices.get.raw'),
                data: {invoice_id : invoice_id},
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $theBtn.siblings('button').removeAttr('disabled');
                $theBtn.removeAttr('disabled');
                $theBtn.find(".theLoader").fadeOut();
                if (response.status == 200) {
                    invoiceConfirmModal.hide();

                    let row = response.data.row;
                    makePaymentModal.show();
                    document.getElementById("makePaymentModal").addEventListener("shown.tw.modal", function (event) {
                        $('#makePaymentModal [name="payment_date"]').val(getTodayDate());
                        $('#makePaymentModal [name="payment_method_id"]').val('');
                        $('#makePaymentModal [name="amount"]').val('');

                        $('#makePaymentModal .totalDue').html('£'+row.invoice_due.toFixed(2));
                        $('#makePaymentModal [name="invoice_id"]').val(invoice_id);
                        $('#makePaymentModal [name="total_amount"]').val(row.invoice_total);
                        $('#makePaymentModal [name="due_amount"]').val(row.invoice_due);
                    });
                }
            }).catch(error =>{
                console.log(error)
            });
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

        $(document).on('click', '#markAsPaidBtn', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            let $form = $theBtn.closest('#invoiceConfirmForm')
            let invoice_id = $form.find('[name="invoice_id"]').val();

            $theBtn.siblings('button').attr('disabled', 'disabled');
            $theBtn.attr('disabled', 'disabled');
            $theBtn.find(".theLoader").fadeIn();

            axios({
                method: 'post',
                url: route('invoices.update.status'),
                data: {invoice_id : invoice_id, pay_status : 'Paid'},
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $theBtn.siblings('button').removeAttr('disabled');
                $theBtn.removeAttr('disabled');
                $theBtn.find(".theLoader").fadeOut();
                if (response.status == 200) {
                    invoiceConfirmModal.hide();

                    successModal.show();
                    document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                        $("#successModal .successModalTitle").html("Congratulations!");
                        $("#successModal .successModalDesc").html(response.data.message);
                        $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                    });

                    setTimeout(() => {
                        successModal.hide();
                        window.location.reload();
                    }, 1500);
                }
            }).catch(error =>{
                $theBtn.siblings('button').removeAttr('disabled');
                $theBtn.removeAttr('disabled');
                $theBtn.find(".theLoader").fadeOut();

                console.log(error)
            });
        })

        $(document).on('click', '.unpaidInvoice', function(e){
            e.preventDefault();
            let $theLink = $(this);
            let invoice_id = $theLink.attr('data-id');

            confirmModal.show();
            document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
                $('#confirmModal .confirmModalTitle').html('Are you sure?');
                $('#confirmModal .confirmModalDesc').html('Do you really want to update the status? Click on agree to continue.');
                $('#confirmModal .agreeWith').attr('data-id', invoice_id);
                $('#confirmModal .agreeWith').attr('data-status', 'Unpaid');
                $('#confirmModal .agreeWith').attr('data-action', 'UPDATESTS');
            });
        })

        $('#confirmModal .agreeWith').on('click', function(){
            let $agreeBTN = $(this);
            let row_id = $agreeBTN.attr('data-id');
            let action = $agreeBTN.attr('data-action');

            $('#confirmModal button').attr('disabled', 'disabled');
            if(action == 'UPDATESTS'){
                let pay_status = $agreeBTN.attr('data-status');
                axios({
                    method: 'post',
                    url: route('invoices.update.status'),
                    data: {invoice_id : row_id, pay_status : pay_status},
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    if (response.status == 200) {
                        $('#confirmModal button').removeAttr('disabled');
                        confirmModal.hide();

                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                        });

                        setTimeout(() => {
                            successModal.hide();
                            window.location.reload();
                        }, 1500);
                    }
                }).catch(error =>{
                    console.log(error)
                });
            }
        })

        $(document).on('click', '.refundInvoice', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            let invoice_id = $theBtn.attr('data-id');

            axios({
                method: 'post',
                url: route('invoices.get.raw'),
                data: {invoice_id : invoice_id},
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $theBtn.siblings('button').removeAttr('disabled');
                $theBtn.removeAttr('disabled');
                $theBtn.find(".theLoader").fadeOut();
                if (response.status == 200) {

                    let row = response.data.row;
                    makeRefundModal.show();
                    document.getElementById("makeRefundModal").addEventListener("shown.tw.modal", function (event) {
                        $('#makeRefundModal [name="payment_date"]').val(getTodayDate());
                        $('#makeRefundModal [name="payment_method_id"]').val('');
                        $('#makeRefundModal [name="amount"]').val('');

                        $('#makeRefundModal .totalDue').html('£'+row.invoice_paid.toFixed(2));
                        $('#makeRefundModal [name="invoice_id"]').val(invoice_id);
                        $('#makeRefundModal [name="total_amount"]').val(row.invoice_total);
                        $('#makeRefundModal [name="paid_amount"]').val(row.invoice_paid);
                    });
                }
            }).catch(error =>{
                console.log(error)
            });
        })

        $('#makeRefundForm').on('input', '[name="amount"]', function(){
            let amount = $(this).val();
            let paid = $('#makeRefundForm').find('[name="paid_amount"]').val() * 1;
            if(amount.length > 0){
                if(amount > paid){
                    $('#makeRefundForm .acc__input-error.error-amount').html('Amount can not grater than the paid amount.').fadeIn();
                    $(this).val('');

                    setTimeout(() => {
                        $('#makeRefundForm .acc__input-error.error-amount').html('').fadeOut();
                    }, 2000);
                }else{
                    $('#makeRefundForm .acc__input-error.error-amount').html('').fadeOut();
                }
            }else{
                $('#makeRefundForm .acc__input-error.error-amount').html('').fadeOut();
            }
        })

        $('#makeRefundForm').on('submit', function(e){
            e.preventDefault();
            const form = document.getElementById('makeRefundForm');
            const $theForm = $(this);
            
            $('#makeRefundForm .acc__input-error').html('').fadeOut();
            $('#refundBtn', $theForm).attr('disabled', 'disabled');
            $("#refundBtn .theLoader").fadeIn();

            let form_data = new FormData(form);
            axios({
                method: "post",
                url: route('invoices.make.refund'),
                data: form_data,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $('#refundBtn', $theForm).removeAttr('disabled');
                $("#refundBtn .theLoader").fadeOut();

                if (response.status == 200) {
                    makeRefundModal.hide();

                    successModal.show();
                    document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                        $("#successModal .successModalTitle").html('Congratulations!');
                        $("#successModal .successModalDesc").html(response.data.message);
                        $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                    });

                    setTimeout(() => {
                        successModal.hide();
                        window.location.reload();
                    }, 1500);
                }
            }).catch(error => {
                $('#refundBtn', $theForm).removeAttr('disabled');
                $("#refundBtn .theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#makeRefundForm .${key}`).addClass('border-danger');
                            $(`#makeRefundForm  .error-${key}`).html(val).fadeIn();
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
    }

    function getTodayDate(){
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1;
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        return dd+'-'+mm+'-'+yyyy;
    }

    
    localStorage.clear();
})();