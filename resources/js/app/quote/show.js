

(function(){
    'use strict';

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const sendEmailModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#sendEmailModal"));

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

                // if(row.quoteNotes){
                //     localStorage.setItem('quoteNotes', JSON.stringify(row.quoteNotes));
                // }
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
            url: route('quotes.send.email'),
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