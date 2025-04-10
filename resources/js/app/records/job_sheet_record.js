import INTAddressLookUps from '../../address_lookup.js';


(function(){
    // INIT Address Lookup
    if($('.theAddressWrap').length > 0){
        INTAddressLookUps();
    }

    const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
    const applianceConfirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#applianceConfirmModal"));

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

    //Trigger Form Input Change
    let formDataChanged = false;
    $('.form-wizard').on('keyup paste', '.show .wizard-step-form input:not([type="radio"]):not([type="checkbox"])', function(){formDataChanged = true;});
    $('.form-wizard').on('keyup paste', '.show .wizard-step-form textarea', function(){formDataChanged = true;});
    $('.form-wizard').on('change', '.show .wizard-step-form select', function(){formDataChanged = true;});
    $('.form-wizard').on('change', '.show .wizard-step-form input[type="radio"]', function(){formDataChanged = true;});
    $('.form-wizard').on('change', '.show .wizard-step-form input[type="checkbox"]', function(){formDataChanged = true;});

    // Toggle N/A Button
    $(document).on('click', '.naToggleBtn', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let thevalue = $theBtn.attr('data-value');

        $theBtn.siblings('input').val(thevalue);
        formDataChanged = true;
    })

    $('.form-wizard-next-btn').on('click', function () {
        var parentFieldset = $(this).parents('.wizard-fieldset');
        var parentForm = $(this).parents('.wizard-step-form');
        var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-steps .active');
        var step_id = parentFieldset.attr('id');
        var next = $(this);
        let nextWizardStep = true;
        let isAppliance = (next.attr('data-appliance') && next.attr('data-appliance') > 0 ? next.attr('data-appliance') : false);

        /* Form Submission Start */
        var formID = parentForm.attr('id');
        const form = document.getElementById(formID);
    
        $('.form-wizard-next-btn, .form-wizard-previous-btn', parentForm).attr('disabled', 'disabled');

        let form_data = new FormData(form);
        let url;
        
        if(parentFieldset.index() == 0){
            url = route('records.store.job.address');
        }else if(parentFieldset.index() == 1){
            url = route('records.store.customer');
        }else if(parentFieldset.index() == 2){
            url = route('records.gjsr.store.details');
            form_data.append('file', $('#jobSheetDetailsForm input#job_sheet_files')[0].files[0]); 
        }else if(parentFieldset.index() == 3){
            url = route('records.gjsr.store.signatures');
        }

        if(url != ''){
            $.ajax({
                method: 'POST',
                url: url,
                data: form_data,
                dataType: 'json',
                async: false,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                success: function(res, textStatus, xhr){
                    $('.form-wizard-next-btn, .form-wizard-previous-btn', parentForm).removeAttr('disabled');
                    if(xhr.status == 200){
                        let saved = res.saved;
                        console.log(saved)
                        if(saved == 1){
                            currentActiveStep.find('.unsavedIcon').fadeOut('fast', function(){
                                currentActiveStep.find('.savedIcon').fadeIn();
                            });
                            parentFieldset.find('.unsavedIcon').fadeOut('fast', function(){
                                parentFieldset.find('.savedIcon').fadeIn();
                            });
                        }else{
                            currentActiveStep.find('.savedIcon').fadeOut('fast', function(){
                                currentActiveStep.find('.unsavedIcon').fadeIn();
                            });
                            parentFieldset.find('.savedIcon').fadeOut('fast', function(){
                                parentFieldset.find('.unsavedIcon').fadeIn();
                            });
                        }
                    }
                    nextWizardStep = true;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('.form-wizard-next-btn, .form-wizard-previous-btn', parentForm).removeAttr('disabled');
                    if(jqXHR.status == 422){
                        for (const [key, val] of Object.entries(jqXHR.responseJSON.errors)) {
                            $(`#${formID} .${key}`).addClass('border-danger');
                            $(`#${formID}  .error-${key}`).html(val);
                        }
                    }else{
                        console.log(textStatus+' => '+errorThrown);
                    }
                    nextWizardStep = false;
                }
            });
        }
        /*if(isAppliance && (isAppliance > 0 && isAppliance < 4)){
            applianceConfirmModal.show();
        }*/
        /* Form Submission End*/

        /* Step Validation Start*/
        var stepError = 0;
        if(stepError > 0){
            nextWizardStep = false;
        }
        /* Step Validation End*/
         
        if (nextWizardStep) {
            next.parents('.wizard-fieldset').removeClass("show");
            currentActiveStep.removeClass('active').addClass('activated').next().addClass('active');
            next.parents('.wizard-fieldset').next('.wizard-fieldset').addClass("show");
            $(document).find('.wizard-fieldset').each(function () {
                if ($(this).hasClass('show')) {
                    var activeIndex = $(this).index();
                    var indexCount = 0;
                    $(document).find('.form-wizard-steps .form-wizard-step-item').each(function () {
                        if (activeIndex == indexCount) {
                            $(this).addClass('active');
                        } else {
                            $(this).removeClass('active');
                        }
                        indexCount++;
                    });
                }
            });
        }
    });

    //Save Final Step
    $(document).on('click', '.form-wizard-final-btn', function(e){
        e.preventDefault();
        $('.gsfSignature .sign-pad-button-submit').trigger('click');
    });
    $('#signatureForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('signatureForm');
        let $form = $(this);
    
        $('.form-wizard-next-btn, .form-wizard-final-btn', $form).attr('disabled', 'disabled');
        $('.form-wizard-final-btn .theIcon', $form).fadeOut();
        $('.form-wizard-final-btn .theLoader', $form).fadeIn();

        let form_data = new FormData(form);
        let url = route('records.gjsr.store.signatures');

        $.ajax({
            method: 'POST',
            url: url,
            data: form_data,
            dataType: 'json',
            async: false,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            success: function(res, textStatus, xhr){
                console.log(res);
                $('.form-wizard-next-btn, .form-wizard-final-btn', $form).removeAttr('disabled');
                $('.form-wizard-final-btn .theLoader', $form).fadeOut();
                $('.form-wizard-final-btn .theIcon', $form).fadeIn();
                if(xhr.status == 200){
                    window.location.href = res.red;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('.form-wizard-next-btn, .form-wizard-final-btn', $form).removeAttr('disabled');
                $('.form-wizard-final-btn .theLoader', $form).fadeOut();
                $('.form-wizard-final-btn .theIcon', $form).fadeIn();
                if(jqXHR.status == 422){
                    for (const [key, val] of Object.entries(jqXHR.responseJSON.errors)) {
                        $(`#signatureForm .${key}`).addClass('border-danger');
                        $(`#signatureForm  .error-${key}`).html(val);
                    }
                }else{
                    console.log(textStatus+' => '+errorThrown);
                }
            }
        });
    })
    
    // ON Previous Button Click
    $('.form-wizard-previous-btn').on('click', function () {
        var counter = parseInt($(".wizard-counter").text());
        
        var prev = $(this);
        var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-steps .active');
        prev.parents('.wizard-fieldset').removeClass("show");
        prev.parents('.wizard-fieldset').prev('.wizard-fieldset').addClass("show");
        currentActiveStep.removeClass('active').prev().removeClass('activated').addClass('active');
        $(document).find('.wizard-fieldset').each(function () {
            if ($(this).hasClass('show')) {
                var activeIndex = $(this).index();
                var indexCount = 0;
                $(document).find('.form-wizard-steps .form-wizard-step-item').each(function () {
                    if (activeIndex == indexCount) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                    indexCount++;
                });
            }
        });
    });

    // Step Buttons ON Click
    $('.form-wizard-steps .form-wizard-step-item').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let theStpeId = $theBtn.attr('data-id');
        let $theTargetedForm = $('#'+theStpeId);
        let $thePrevActiveBtn = $('.form-wizard-steps .form-wizard-step-item.active');
        
        var $currentActiveBtn = $('.form-wizard').find('.form-wizard-steps .active');
        let $currentActiveForm = $('.wizard-fieldset.show');

        $currentActiveForm.removeClass("show");
        $theTargetedForm.addClass("show");
        //$theTargetedForm.find('.wizard-step-form').css({'display' : 'block'});

        $thePrevActiveBtn.removeClass('active');
        $theBtn.addClass('active');
    });

    // Mobile Device Header ON Click
    $('.wizard-fieldset .wizard-fieldset-header').on('click', function(e){
        e.preventDefault();
        let $currentActiveFieldset = $('.form-wizard .wizard-fieldset.show');
        let $currentActiveForm = $currentActiveFieldset.find('.wizard-step-form');
        let parentFieldset = $currentActiveForm.parents('.wizard-fieldset');
        let currentActiveStep = $('.form-wizard-steps .form-wizard-step-item.active');

        let $theHeader = $(this);
        let $parentfieldSet = $theHeader.parents('.wizard-fieldset');
        let theFieldSetIndex = $parentfieldSet.index();

        let $allStepBtns = $('.form-wizard-steps .form-wizard-step-item');
        let $allFieldSets = $('.form-wizard .wizard-fieldset');
        
        
        if($parentfieldSet.hasClass('show')){
            $allStepBtns.eq(theFieldSetIndex).removeClass('active');
            $parentfieldSet.removeClass('show');
        }else{
            if($currentActiveForm.length > 0){
                let formid = $currentActiveForm.attr('id');
                const form = document.getElementById(formid);
                let form_data = new FormData(form);
                let url;
                
                
                if(parentFieldset.index() == 0){
                    url = route('records.store.job.address');
                }else if(parentFieldset.index() == 1){
                    url = route('records.store.customer');
                }else if(parentFieldset.index() == 2){
                    url = route('records.gjsr.store.details');
                    form_data.append('file', $('#jobSheetDetailsForm input#job_sheet_files')[0].files[0]); 
                }else if(parentFieldset.index() == 3){
                    url = route('records.gjsr.store.signatures');
                }

                if(url != ''){
                    $.ajax({
                        method: 'POST',
                        url: url,
                        data: form_data,
                        dataType: 'json',
                        async: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                        contentType: false,
                        cache: false,
                        headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                        success: function(res, textStatus, xhr){
                            if(xhr.status == 200){
                                let saved = res.saved;
                                if(saved == 1){
                                    currentActiveStep.find('.unsavedIcon').fadeOut('fast', function(){
                                        currentActiveStep.find('.savedIcon').fadeIn();
                                    });
                                    $currentActiveFieldset.find('.unsavedIcon').fadeOut('fast', function(){
                                        $currentActiveFieldset.find('.savedIcon').fadeIn();
                                    });
                                }else{
                                    currentActiveStep.find('.savedIcon').fadeOut('fast', function(){
                                        currentActiveStep.find('.unsavedIcon').fadeIn();
                                    });
                                    $currentActiveFieldset.find('.savedIcon').fadeOut('fast', function(){
                                        $currentActiveFieldset.find('.unsavedIcon').fadeIn();
                                    });
                                }
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            if(jqXHR.status == 422){
                                console.log(textStatus+' => '+errorThrown);
                            }
                        }
                    });
                }
            }
            $allStepBtns.removeClass('active');
            $allFieldSets.removeClass('show');

            $allStepBtns.eq(theFieldSetIndex).addClass('active');
            $parentfieldSet.addClass('show');

            if($parentfieldSet.find('.e-signpad').length > 0){
                resizeCanvasOverwrite();
            }
        }
    });

    // Appliance SKip Or Continue Modal Buttons On Click
    $('#applianceConfirmModal').on('click', '.agreeMore', function(e){
        e.preventDefault();
        let $currentActiveTab = $('.form-wizard').find('.form-wizard-step-item.active');
        let currentActiveTabIndex = $currentActiveTab.index();
        let dataAppliance = ($currentActiveTab.attr('data-appliance') && $currentActiveTab.attr('data-appliance') > 0 ? $currentActiveTab.attr('data-appliance') : 0);
        let $currentActiveForm = $('.form-wizard').find('.wizard-fieldset.show');
        let currentActiveFormIndex = $currentActiveForm.index();

        let nextTabIndex = currentActiveTabIndex + 1;
        let nextDataAppliance = parseInt(dataAppliance, 10) + 1;
        let nextFormIndex = currentActiveFormIndex + 1;

        applianceConfirmModal.hide();
        $currentActiveForm.removeClass("show");
        $('.form-wizard').find('.wizard-fieldset').eq(nextFormIndex).addClass("show");

        $currentActiveTab.removeClass('active');
        $('.form-wizard').find('.form-wizard-step-item').eq(nextFormIndex).addClass('active');
    });
    $('#applianceConfirmModal').on('click', '.canceleMore', function(e){
        e.preventDefault();
        let $currentActiveTab = $('.form-wizard').find('.form-wizard-step-item.active');
        let currentActiveTabIndex = $currentActiveTab.index();
        let dataAppliance = ($currentActiveTab.attr('data-appliance') && $currentActiveTab.attr('data-appliance') > 0 ? $currentActiveTab.attr('data-appliance') : 0);
        let $currentActiveForm = $('.form-wizard').find('.wizard-fieldset.show');
        let currentActiveFormIndex = $currentActiveForm.index();

        let RemainingAppliance = (4 - dataAppliance) + 1;
        let nextTabIndex = currentActiveTabIndex + RemainingAppliance;
        let nextFormIndex = currentActiveFormIndex + RemainingAppliance;

        applianceConfirmModal.hide();
        $currentActiveForm.removeClass("show");
        $('.form-wizard').find('.wizard-fieldset').eq(nextFormIndex).addClass("show");

        $currentActiveTab.removeClass('active');
        $('.form-wizard').find('.form-wizard-step-item').eq(nextFormIndex).addClass('active');
    });

    let resizeCanvasOverwrite = () => {
            document.querySelectorAll('.wizard-fieldset.show .e-signpad').forEach(function(eSignpad) {
            let canvas = eSignpad.querySelector('canvas'),
                submit = eSignpad.querySelector('.sign-pad-button-submit'),
                clear = eSignpad.querySelector('.sign-pad-button-clear');
                
            if (window.innerWidth < 768 && (canvas.width > window.innerWidth || canvas.width < 300)) {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
    
                canvas.width = canvas.parentNode.parentElement.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                clear.click();
            }
        });
    }


    // Custom Upload
    $(document).on('change', '#job_sheet_files', function(){
        let input = document.getElementById('job_sheet_files');
        if (input.files) {
            const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/jpg'];
            var filesAmount = input.files.length;

            $('#uploadedFileWrap').find('.justIn').remove();

            for (var i = 0; i < filesAmount; i++) {
                let fileType = input.files[i]['type'];
                if(validImageTypes.includes(fileType)){
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $('#uploadedFileWrap').append('<span class="justIn inline-flex items-center justify-center image-fit h-[80px] w-[80px] bg-success bg-opacity-10 rounded-[3px] overflow-hidden mr-1 mb-1"><img class="rounded-[3px]" src="'+event.target.result+'"></span>');
                    }
            
                    reader.readAsDataURL(input.files[i]);
                }else{
                    $('#uploadedFileWrap').append('<span class="justIn inline-flex items-center justify-center h-[80px] w-[80px] bg-success bg-opacity-10 rounded-[3px] overflow-hidden mr-1 mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="file-text" class="lucide lucide-file-text stroke-1.5 h-8 w-8 text-success"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg></span>');
                }
            }

            $('.customeUploads .customeUploadsContent').fadeOut('fast', function(){
                $('#uploadedFileWrap').fadeIn();
            });
        }else{
            $('#uploadedFileWrap').fadeOut('fast', function(){
                $('.customeUploads .customeUploadsContent').fadeIn('fast', function(){
                    $('#job_sheet_files').val('');
                });
            })
        }
    });


    $(document).on('click', '.delete-doc', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let row_id = $theBtn.attr('data-id');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete this document? If yes then please click on the agree btn.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETEDOC');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETEDOC'){
            axios({
                method: 'delete',
                url: route('records.gjsr.destroy.document', row_id),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                if (response.status == 200) {
                    $('#gjsr_doc_'+row_id).remove();
                    $('#confirmModal button').removeAttr('disabled');
                    confirmModal.hide();

                    successModal.show();
                    document.getElementById('successModal').addEventListener('shown.tw.modal', function(event){
                        $('#successModal .successModalTitle').html('WOW!');
                        $('#successModal .successModalDesc').html(response.data.msg);
                        $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                    });

                    setTimeout(() => {
                        successModal.show();
                    }, 1500);
                }
            }).catch(error =>{
                console.log(error)
            });
        }
    });

})();