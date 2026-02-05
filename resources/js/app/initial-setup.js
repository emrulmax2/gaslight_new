import { route } from 'ziggy-js';
import INTAddressLookUps from '../address_lookup';

("use strict");
(function () { 
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        INTAddressLookUps();
    });

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

    $(document).on('click', '#companySetupBtn', function(e){
        e.preventDefault();
        $('.gsfSignature .sign-pad-button-submit').trigger('click');
        setTimeout(() => {
            $('#step1-form').trigger('submit');
        }, 100);
    });


    //Store Form    
    $('#step1-form').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('step1-form');
        const $theForm = $(this);
        
        $('#companySetupBtn', $theForm).attr('disabled', 'disabled');
        $("#companySetupBtn .theLoader").fadeIn();

        let form_data = new FormData(this);
        
        axios({
            method: "POST",
            url: route('company.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            console.log(response);
            
            $('#companySetupBtn', $theForm).removeAttr('disabled');
            $("#companySetupBtn .theLoader").fadeOut();

            if (response.status == 200) {
                
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                });

                

                setTimeout(() => {
                    successModal.hide();
                    window.location.href = response.data.red;
                }, 1500);
            }
        }).catch(error => {
            $('#companySetupBtn', $theForm).removeAttr('disabled');
            $("#companySetupBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#step1-form .${key}`).addClass('border-danger');
                        $(`#step1-form  .error-${key}`).html(val);
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

    // Signature Toggle
    $('.intSetupSignatureBtns .signBtns').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $('.intSetupSignatureBtns .uploadBtns').removeClass('active');
        $theBtn.addClass('active');

        $('.gsfSignature .customeUploads').fadeOut('fast', function(){
            $('.gsfSignature .e-signpad').fadeIn();
            $('#signature_image').fadeOut('fast', function(){
                $('.customeUploads .customeUploadsContent').fadeIn();
                $('#signature_file').val('');
            })
        });
        
    })
    $('.intSetupSignatureBtns .uploadBtns').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $('.intSetupSignatureBtns .signBtns').removeClass('active');
        $theBtn.addClass('active');

        $('.gsfSignature').find('.sign-pad-button-clear').trigger('click');
        $('.gsfSignature .e-signpad').fadeOut('fast', function(){
            $('.gsfSignature .customeUploads').fadeIn();
        });
    });

    $(document).on('change', '#signature_file', function(){
        if($('#signature_file').get(0).files.length === 0){
            $('#signature_image').fadeOut('fast', function(){
                $('.customeUploads .customeUploadsContent').fadeIn();
            })
        }else{
            if(this.files[0].size > 2097152){
                $('#signature_file').val('');
                $('#signature_image').fadeOut('fast', function(){
                    $('.customeUploads .customeUploadsContent').fadeIn('fast', function(){
                        $('.customeUploads .customeUploadsContent .sizeError').remove();
                        $('.customeUploads .customeUploadsContent').append('<div role="alert" class="sizeError inline-flex alert relative border rounded-md px-3 py-2 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mt-3 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg>File size must not be more than 2 MB</div>')
                        
                        setTimeout(() => {
                            $('.customeUploads .customeUploadsContent .sizeError').remove();
                        }, 2000);
                    });
                })
            }else{
                $('.customeUploads .customeUploadsContent').fadeOut('fast', function(){
                    showPreview('signature_file', 'signature_image');
                    $('#signature_image').fadeIn();
                })
            }
        }
    })

    function showPreview(inputId, targetImageId) {
        var src = document.getElementById(inputId);
        var target = document.getElementById(targetImageId);
        var title = document.getElementById('selected_image_title');
        var fr = new FileReader();
        fr.onload = function () {
            target.src = fr.result;
        }
        fr.readAsDataURL(src.files[0]);
    };


    //Business type
    function handleCompanyTypeChange() {
        const selectedBusinessType = document.querySelector('input[name="business_type"]:checked');
        const companyType = selectedBusinessType ? selectedBusinessType.value : null;
        const companyRegisterNo = document.getElementById("company_register_no");

        if (companyType === "Company") {
            companyRegisterNo.style.display = "block";
        } else {
            companyRegisterNo.style.display = "none";
        }
    }

    // Add event listener to company_type radio buttons
    document.querySelectorAll('input[name="business_type"]').forEach((radio) => {
        radio.addEventListener("click", handleCompanyTypeChange);
    });

    // Initial check on page load
    handleCompanyTypeChange();

    //Show Vat
    const vatEnableCheckbox = document.getElementById("vat_number");
    const vatNumberInput = document.querySelector(".vat_number_input");


    vatEnableCheckbox.addEventListener("change", function () {
        
        if (this.checked) {
            vatNumberInput.classList.remove("hidden");
        } else {
            vatNumberInput.classList.add("hidden");
        }
    })

})();