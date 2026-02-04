

(function(){
    'use strict';

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const makePaymentModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#makePaymentModal"));
    const sendEmailModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#sendEmailModal"));

    document.getElementById('successModal').addEventListener('hide.tw.modal', function(event) {
        $('#successModal .agreeWith').attr('data-action', 'NONE').attr('data-redirect', '');
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

    let lastActiveField = null; // last active field (input or CKEditor)
    let theEditor = null;

    // Track input focus and mousedown
    const input = document.getElementById('subject');
    input.addEventListener('mousedown', () => lastActiveField = input);
    input.addEventListener('focus', () => lastActiveField = input);

    // Initialize CKEditor
    ClassicEditor.create(document.getElementById('theEditor'))
        .then(editor => {
            theEditor = editor;

            // Optional: move toolbar
            const toolbarContainer = document.querySelector('.document-editor__toolbar');
            if (toolbarContainer) {
                toolbarContainer.appendChild(editor.ui.view.toolbar.element);
            }

            // Track CKEditor focus
            editor.editing.view.document.on('focus', () => lastActiveField = editor);
        })
        .catch(error => console.error(error));

    if($('.emailTags').length > 0){
        $(document).on('click', '.emailTags a.dropdown-item', function(e){
            var theText = $(this).text();
            navigator.clipboard.writeText(theText);
            Toastify({
                node: $("#coppiedNodeEl")
                    .clone()
                    .removeClass("hidden")[0],
                duration: 2000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
            }).showToast();
        });
    }

    // Tag buttons
    const tagButtons = document.querySelectorAll('.theTagItem');
    tagButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tagText = this.textContent;
            if (!lastActiveField) return;

            // CKEditor active
            if (lastActiveField === theEditor) {
                theEditor.model.change(writer => {
                    const selection = theEditor.model.document.selection;
                    if (!selection.isCollapsed) {
                        writer.remove(selection.getFirstRange());
                    }
                    writer.insertText(tagText, selection.getFirstPosition());
                });
                theEditor.editing.view.focus();
                return;
            }

            // Input field active
            const start = lastActiveField.selectionStart;
            const end = lastActiveField.selectionEnd;
            const val = lastActiveField.value;
            lastActiveField.value = val.substring(0, start) + tagText + val.substring(end);

            lastActiveField.selectionStart = lastActiveField.selectionEnd = start + tagText.length;
            lastActiveField.focus();
        });
    });

    $('#attachments').on('change', function(){
        let $theAttachment = $("#attachments");
        let selectedLength = $theAttachment[0].files.length; 
        let selectedItems = $theAttachment[0].files;
        let attachmentCount = $('.attachmentCount').attr('data-prevcount') * 1;
        $('.error-attachments_error').html('')
        
        let SingleFileSize = 5242880;
        let AllFileSize = 20971520;
        let totalUploadSize = 0;
        let SingleFileError = 0;
        if (selectedLength > 0) {
            for (var i = 0; i < selectedLength; i++) {
                totalUploadSize = totalUploadSize + selectedItems[i].size;
                if(selectedItems[i].size > SingleFileSize){
                    SingleFileError += 1;
                }
            }

            if(SingleFileError > 0){
                $('#attachments').val('');
                $('.attachmentCount').html((attachmentCount + 0)+' Attachments');
                $('.error-attachments_error').html('One of you selected file exceeded single file size.')
            }else if(totalUploadSize > AllFileSize){
                $('#attachments').val('');
                $('.attachmentCount').html((attachmentCount + 0)+' Attachments');
                $('.error-attachments_error').html('Selected items size exceeded your total upload limit.')
            }else{
                $('.attachmentCount').html((selectedLength == 1 ? (attachmentCount + 1)+' Attachment' : (attachmentCount +selectedLength)+' Attachments'));
                $('.error-attachments_error').html('')
            }
        }
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
                localStorage.setItem('billing_address', JSON.stringify(row.billing_address));

                
                // if(row.invoiceNotes){
                //     localStorage.setItem('invoiceNotes', JSON.stringify(row.invoiceNotes));
                // }
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

    $('#sendEmailForm').on('submit', function(e){
        e.preventDefault();
        let $theForm = $(this);
        const form = document.getElementById('sendEmailForm');
    
        $('#sendEmailBtn', $theForm).attr('disabled', 'disabled');
        $("#sendEmailBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        //form_data.append('file', $('#sendEmailForm #attachments')[0].files[0]); 
        form_data.append("content", theEditor.getData());
        axios({
            method: "post",
            url: route('invoices.send.email'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#sendEmailBtn', $theForm).removeAttr('disabled');
            $("#sendEmailBtn .theLoader").fadeOut();

            if (response.status == 200) {
                sendEmailModal.hide();
                
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
            $('#sendEmailBtn', $theForm).removeAttr('disabled');
            $("#sendEmailBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#sendEmailForm .${key}`).addClass('border-danger');
                        $(`#sendEmailForm  .error-${key}`).html(val);
                    }
                } else {
                    console.log('error');
                }
            }
        });
    });
})()