

(function(){
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


    $('#reminderTemplateForm').on('submit', function(e){
        e.preventDefault();
        let $theForm = $(this);
        const form = document.getElementById('reminderTemplateForm');
    
        $('#saveEmailTemplatesBtn', $theForm).attr('disabled', 'disabled');
        $("#saveEmailTemplatesBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        form_data.append('file', $('#reminderTemplateForm #attachments')[0].files[0]); 
        form_data.append("content", theEditor.getData());
        axios({
            method: "post",
            url: route('user.settings.reminder.templates.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#saveEmailTemplatesBtn', $theForm).removeAttr('disabled');
            $("#saveEmailTemplatesBtn .theLoader").fadeOut();

            if (response.status == 200) {
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.href = response.data.red;
                }, 1500);
            }
        }).catch(error => {
            $('#saveEmailTemplatesBtn', $theForm).removeAttr('disabled');
            $("#saveEmailTemplatesBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#saveEmailTemplatesBtn .${key}`).addClass('border-danger');
                        $(`#saveEmailTemplatesBtn  .error-${key}`).html(val);
                    }
                } else {
                    console.log('error');
                }
            }
        });
    });

    $('#attachments').on('change', function(){
        let $theAttachment = $("#attachments");
        let selectedLength = $theAttachment[0].files.length; 
        let selectedItems = $theAttachment[0].files;
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
                $('.attachmentCount').html('0 Attachments');
                $('.error-attachments_error').html('One of you selected file exceeded single file size.')
            }else if(totalUploadSize > AllFileSize){
                $('#attachments').val('');
                $('.attachmentCount').html('0 Attachments');
                $('.error-attachments_error').html('Selected items size exceeded your total upload limit.')
            }else{
                $('.attachmentCount').html((selectedLength == 1 ? '1 Attachment' : selectedLength+' Attachments'));
                $('.error-attachments_error').html('')
            }
        }
    });

    
    $(document).on('click', '.delete_attachment', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete these record? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETETDOC');
        });
    });
    
    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETETDOC'){
            axios({
                method: 'delete',
                url: route('user.settings.reminder.templates.destroy.attachment', row_id),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if(response.status == 200) {
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
                        window.location.href = response.data.red;
                    }, 1500);
                }
            }).catch(error =>{
                console.log(error)
            });
        }else if(action == 'RELOADTEMPLATE'){
            axios({
                method: 'POST',
                url: route('user.settings.reminder.templates.reload'),
                data: {'form_id' : row_id},
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    let row = response.data.row;
                    if(row.subject != ''){
                        $('#subject').val(row.subject);
                    }
                    if(row.content != ''){
                        theEditor.setData(row.content);
                    }
                }
            }).catch(error =>{
                $('#confirmModal button').removeAttr('disabled');
                if (error.response) {
                    if (error.response.status == 304) {
                        confirmModal.hide();
                        
                        warningModal.show();
                        document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                            $("#warningModal .warningModalTitle").html("Error Found!");
                            $("#warningModal .warningModalDesc").html('Base template not found.');
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
    })


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

    // Reload Data
    $(document).on('click', '#reloadTemplate', function(){
        let $statusBTN = $(this);
        let row_id = $statusBTN.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to reload this template from base template? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'RELOADTEMPLATE');
        });
    });

})()