import { route } from 'ziggy-js';
import INTAddressLookUps from '../address_lookup.js';

("use strict");
(function () { 
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

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
    $('#step1-form').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('step1-form');
        const $theForm = $(this);
        
        $('#companySetupBtn', $theForm).attr('disabled', 'disabled');
        $("#companySetupBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('company.store'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
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
    console.log(vatNumberInput)


    vatEnableCheckbox.addEventListener("change", function () {
        console.log('hello');
        
        if (this.checked) {
            vatNumberInput.classList.remove("hidden");
        } else {
            vatNumberInput.classList.add("hidden");
        }
    })

})();