

(function(){
    'use strict';

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));

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

    $('#quoteForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('quoteForm');
        let $theForm = $(this);
        let type = $theForm.find('[name="submit_type"]').val();
        let qut_id = $theForm.find('#qut_id').val();

        $('.formSubmits', $theForm).attr('disabled', 'disabled');
        $('.formSubmits.submit_'+type+' .theLoader', $theForm).fadeIn();
        $('.formSubmits.submit_'+type, $theForm).addClass('active');

        let form_data = new FormData(form);
        axios({
            method: "post",
            url: route('quote.store', qut_id),
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

    /* BEGIN: Quote To Invoice */
    $('#convertQuotToInvBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let quote_id = $theBtn.attr('data-id');

        $('.theLoader', $theBtn).fadeIn();
        $theBtn.addClass('active');
        $theBtn.siblings('button').attr('disabled', 'disabled');
        
        axios({
            method: "post",
            url: route('quote.convert.to.invoice'),
            data: { quote_id : quote_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
                $('.theLoader', $theBtn).fadeOut();
                $theBtn.removeClass('active');
                $theBtn.siblings('button').removeAttr('disabled');

            if (response.status == 200) {
                let row = response.data.row;
                
                localStorage.setItem('invoice_id', row.invoice_id);
                localStorage.setItem('invoiceDetails', JSON.stringify(row.invoiceDetails));
                localStorage.setItem('job', JSON.stringify(row.job));
                localStorage.setItem('customer', JSON.stringify(row.customer));
                localStorage.setItem('job_address', JSON.stringify(row.job_address));
                localStorage.setItem('occupant', JSON.stringify(row.occupant));
                localStorage.setItem('invoiceNumber', row.invoiceNumber);
                if(row.invoiceNotes){
                    localStorage.setItem('invoiceNotes', JSON.stringify(row.invoiceNotes));
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

                window.location.href = response.data.red
            }
        }).catch(error => {
            $('.theLoader', $theBtn).fadeOut();
            $theBtn.removeClass('active');
            $theBtn.siblings('button').removeAttr('disabled');

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
    })
    /* END: Quote To Invoice */
    
    $('.editRecordBtn').on('click', function(e){
        e.preventDefault();
        var $theBtn = $(this);
        var record_id = $theBtn.attr('data-id');

        $theBtn.addClass('active').attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();
        $theBtn.siblings('.action_btns').removeClass('active').attr('disabled', 'disabled');

        axios({
            method: "post",
            url: route('quote.edit.ready.new'),
            data: {record_id : record_id},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                let row = response.data.row;
                
                localStorage.setItem('quote_id', row.quote_id);
                localStorage.setItem('quoteDetails', JSON.stringify(row.quoteDetails));
                localStorage.setItem('job', JSON.stringify(row.job));
                localStorage.setItem('customer', JSON.stringify(row.customer));
                localStorage.setItem('job_address', JSON.stringify(row.job_address));
                localStorage.setItem('occupant', JSON.stringify(row.occupant));
                localStorage.setItem('quoteNumber', row.quoteNumber);
                if(row.quoteNotes){
                    localStorage.setItem('quoteNotes', JSON.stringify(row.quoteNotes));
                }
                if(row.quoteItems){
                    localStorage.setItem('quoteItemsCount', row.quoteItemsCount);
                    localStorage.setItem('quoteItems', JSON.stringify(row.quoteItems));
                }
                if(row.quoteDiscounts){
                    localStorage.setItem('quoteDiscounts', JSON.stringify(row.quoteDiscounts));
                }
                if(row.quoteAdvance){
                    localStorage.setItem('quoteAdvance', JSON.stringify(row.quoteAdvance));
                }

                window.location.href = response.data.red
            }
        }).catch(error => {
            if (error.response) {
                console.log('error');
            }
        });
    })
})()