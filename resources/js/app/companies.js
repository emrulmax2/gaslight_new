import INTAddressLookUps from '../address_lookup.js';

("use strict");
(function () { 
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const companyInformationModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#companyInformationModal"));
    const companyRegistrationModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#companyRegistrationModal"));
    const companyContactModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#companyContactModal"));
    const companyAddressModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#companyAddressModal"));
    const companyBankModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#companyBankModal"));
    
    
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

    $('#companyStoreForm #business_type').on('change', function(){
        if($(this).val() == 'Company'){
            $('#companyStoreForm .registrationWrap').fadeIn('fast', function(){
                $('input', this).val('');
            });
        }else{
            $('#companyStoreForm .registrationWrap').fadeOut('fast', function(){
                $('input', this).val('');
            });
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
            method: "POST",
            url: route('company.update'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#saveCompanyBtn', $theForm).removeAttr('disabled');
            $("#saveCompanyBtn .theLoader").fadeOut();

            if (response.status == 200) {
                
                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                });

                setTimeout(function(){
                    successModal.hide();
                }, 1500)
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


    const vatEnableCheckbox = document.getElementById("vat_number_check");
    const vatNumberInput = document.querySelector(".vat_number_input");
    vatEnableCheckbox.addEventListener("change", function () {
        if (this.checked) {
            vatNumberInput.classList.remove("hidden");
        } else {
            vatNumberInput.classList.add("hidden");
        }
    })

    // Store Company Information
    $('#companyInformationForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('companyInformationForm');
        const $theForm = $(this);
        
        $('#piUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#piUpdateBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('company.update.company.info'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#piUpdateBtn', $theForm).removeAttr('disabled');
            $("#piUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                companyInformationModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(function(){
                    successModal.hide();
                    window.location.reload();
                }, 1500)
            }
        }).catch(error => {
            $('#piUpdateBtn', $theForm).removeAttr('disabled');
            $("#piUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#companyInformationForm .${key}`).addClass('border-danger');
                        $(`#companyInformationForm  .error-${key}`).html(val);
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
    });

    // Store Company Registration Details
    $('#companyRegistrationForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('companyRegistrationForm');
        const $theForm = $(this);
        
        $('#rdUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#rdUpdateBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('company.update.registration.info'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#rdUpdateBtn', $theForm).removeAttr('disabled');
            $("#rdUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                companyRegistrationModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(function(){
                    successModal.hide();
                    window.location.reload();
                }, 1500)
            }
        }).catch(error => {
            $('#rdUpdateBtn', $theForm).removeAttr('disabled');
            $("#rdUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#companyRegistrationForm .${key}`).addClass('border-danger');
                        $(`#companyRegistrationForm  .error-${key}`).html(val);
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
    });

    // Store Company Contact Details
    $('#companyContactForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('companyContactForm');
        const $theForm = $(this);
        
        $('#cdUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#cdUpdateBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('company.update.contact.info'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#cdUpdateBtn', $theForm).removeAttr('disabled');
            $("#cdUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                companyContactModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(function(){
                    successModal.hide();
                    window.location.reload();
                }, 1500)
            }
        }).catch(error => {
            $('#cdUpdateBtn', $theForm).removeAttr('disabled');
            $("#cdUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#companyContactForm .${key}`).addClass('border-danger');
                        $(`#companyContactForm  .error-${key}`).html(val);
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
    });

    // Store Company Address Details
    $('#companyAddressForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('companyAddressForm');
        const $theForm = $(this);
        
        $('#adrUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#adrUpdateBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('company.update.address.info'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#adrUpdateBtn', $theForm).removeAttr('disabled');
            $("#adrUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                companyAddressModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(function(){
                    successModal.hide();
                    window.location.reload();
                }, 1500)
            }
        }).catch(error => {
            $('#adrUpdateBtn', $theForm).removeAttr('disabled');
            $("#adrUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#companyAddressForm .${key}`).addClass('border-danger');
                        $(`#companyAddressForm  .error-${key}`).html(val);
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
    });

    // Store Company Bank Details
    $('#companyBankForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('companyBankForm');
        const $theForm = $(this);
        
        $('#bdUpdateBtn', $theForm).attr('disabled', 'disabled');
        $("#bdUpdateBtn .theLoader").fadeIn();

        let form_data = new FormData(form);
        axios({
            method: "POST",
            url: route('company.update.bank.info'),
            data: form_data,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            $('#bdUpdateBtn', $theForm).removeAttr('disabled');
            $("#bdUpdateBtn .theLoader").fadeOut();

            if (response.status == 200) {
                companyBankModal.hide();

                successModal.show();
                document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                    $("#successModal .successModalTitle").html("Congratulations!");
                    $("#successModal .successModalDesc").html(response.data.msg);
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                });

                setTimeout(function(){
                    successModal.hide();
                    window.location.reload();
                }, 1500)
            }
        }).catch(error => {
            $('#bdUpdateBtn', $theForm).removeAttr('disabled');
            $("#bdUpdateBtn .theLoader").fadeOut();
            if (error.response) {
                if (error.response.status == 422) {
                    for (const [key, val] of Object.entries(error.response.data.errors)) {
                        $(`#companyBankForm .${key}`).addClass('border-danger');
                        $(`#companyBankForm  .error-${key}`).html(val);
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
    });

})();