("use strict");
import { route } from 'ziggy-js';
(function () { 
    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    
    
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
 //Store Form    
$('#companyStoreForm').on('submit', function(e){
    e.preventDefault();
    const form = document.getElementById('companyStoreForm');
    const $theForm = $(this);
    
    $('#saveCompanyBtn', $theForm).attr('disabled', 'disabled');
    $("#saveCompanyBtn .theLoader").fadeIn();

    let form_data = new FormData(form);
    axios({
        method: "post",
        url: route('company.store'),
        data: form_data,
        headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
    }).then(response => {
        $('#saveCompanyBtn', $theForm).removeAttr('disabled');
        $("#saveCompanyBtn .theLoader").fadeOut();

        if (response.status == 200) {
            window.location.reload();
            
            successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });
            
        }
    }).catch(error => {
        $('#saveCompanyBtn', $theForm).removeAttr('disabled');
        $("#saveCompanyBtn .theLoader").fadeOut();
        if (error.response) {
            if (error.response.status == 422) {
                for (const [key, val] of Object.entries(error.response.data.errors)) {
                    $(`#companyStoreForm .${key}`).addClass('border-danger');
                    $(`#companyStoreForm  .error-${key}`).html(val);
                }
            } else if (error.response.status == 304) {
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

$("#fileInput").on("change", function (event) {
    var file = event.target.files[0];

    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var imageSrc = e.target.result;

            $("#thumbnail-preview-container").html(`
                <div class="relative inline-block">
                    <img src="${imageSrc}" alt="Preview" class="h-24 w-24 border border-gray-300 rounded-md p-1 shadow-xl mt-2 hover:shadow-lg">
                    <button class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full w-6 h-6 flex items-center justify-center text-sm remove-thumbnail" type="button">&times;</button>
                </div>
            `);

            $(".file-upload-content").removeClass("hidden");

            $(".remove-thumbnail").on("click", function () {
                $("#fileInput").val("");
                $("#thumbnail-preview-container").empty();
                $(".file-upload-content").addClass("hidden");
            });
        };
        reader.readAsDataURL(file);
    }
});

})();