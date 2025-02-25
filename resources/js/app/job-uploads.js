
(function(){
    "use strict";

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const jobUploadDocModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#jobUploadDocModal"));

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

    if($('#jobUploadDocForm').length > 0){
        Dropzone.autoDiscover = false;
        let jobDropZone = new Dropzone("#jobUploadDocForm", {
            autoProcessQueue: false,
            maxFiles: 5,
            maxFilesize: 20,
            parallelUploads: 5,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.xl,.xls,.xlsx,.doc,.docx,.ppt,.pptx,.txt",
            addRemoveLinks: true,
            accept: function(file, done) {
                if(file.name.match(/[`!@#$%^&*+\=\[\]{};':"\\|,<>\/?~]/)){
                    done("Oops! Your selected file name contain invalid character. Please rename and upload again.");
                }
                else { done(); }
            },
            success: function (file, response) {
                console.log('Success')
            },
            complete: function (file) {
                console.log('Complete')
                
            },
            error: function (file, response) {
                console.error(response);
                let errorMessage = typeof response === "string" ? response : response.message;

                $('#jobUploadDocModal .modal-body .uploadError').remove();
                $('#jobUploadDocModal .modal-body').prepend('<div role="alert" class="uploadError alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-5 flex items-center"><i data-tw-merge data-lucide="alert-octagon" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>'+errorMessage+'</div>');
                createIcons({icons, attrs: { "stroke-width": 1.5 }, nameAttr: "data-lucide"});

                jobDropZone.removeFile(file);
                setTimeout(function(){
                    $('#jobUploadDocModal .modal-body .uploadError').remove();
                }, 3000)

                console.log(errorMessage)
            },
            queuecomplete: function(){
                $('#uploadJobDocumentsBtn').removeAttr('disabled');
                $('#uploadJobDocumentsBtn').find('.theLoader').fadeOut();

                jobUploadDocModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html('Job files successfully uploaded.');
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                });

                setTimeout(() => {
                    successModal.hide();
                    window.location.reload();
                }, 1500);
            },
        });

        $('#uploadJobDocumentsBtn').on('click', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            $theBtn.attr('disabled', 'disabled');
            $theBtn.find('.theLoader').fadeIn();
            
            if(jobDropZone.files.length > 0){
                jobDropZone.processQueue();
            }else{
                $theBtn.removeAttr('disabled');
                $theBtn.find('.theLoader').fadeOut();

                $('#jobUploadDocModal .modal-body .uploadError').remove();
                $('#jobUploadDocModal .modal-body').prepend('<div role="alert" class="uploadError alert relative border rounded-md px-5 py-4 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-5 flex items-center"><i data-tw-merge data-lucide="alert-octagon" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>Oops! Please select at least one file.</div>');
                createIcons({icons, attrs: { "stroke-width": 1.5 }, nameAttr: "data-lucide"});

                setTimeout(function(){
                    $('#jobUploadDocModal .modal-body .uploadError').remove();
                }, 3000)
            }
        });
    }

    


})();