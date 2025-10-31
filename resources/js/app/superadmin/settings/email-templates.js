

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

    let theEditor;
    if($("#theEditor").length > 0){
        const el = document.getElementById('theEditor');
        ClassicEditor.create(el).then((editor) => {
            theEditor = editor;
            $(el).closest(".editor").find(".document-editor__toolbar").append(editor.ui.view.toolbar.element);
        }).catch((error) => {
            console.error(error);
        });
    }

    $('#reminderTemplateForm').on('submit', function(e){
        e.preventDefault();
        let $theForm = $(this);
        const form = document.getElementById('reminderTemplateForm');
    
        $('#saveEmailTemplatesBtn', $theForm).attr('disabled', 'disabled');
        $("#saveEmailTemplatesBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        form_data.append("content", theEditor.getData());
        axios({
            method: "post",
            url: route('superadmin.site.setting.email.template.store'),
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

})()