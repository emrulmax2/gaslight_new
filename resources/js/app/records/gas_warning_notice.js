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
        console.log(parentFieldset.index())
        if(parentFieldset.index() == 0){
            url = route('records.store.job.address');
        }else if(parentFieldset.index() == 1){
            url = route('records.store.customer');
        }else if(parentFieldset.index() == 2 || parentFieldset.index() == 3 || parentFieldset.index() == 4 || parentFieldset.index() == 5){
            url = route('records.store.appliance');
        }else if(parentFieldset.index() == 6){
            url = route('records.store.satisfactory.check');
        }else if(parentFieldset.index() == 7){
            url = route('records.store.comments');
        }else if(parentFieldset.index() == 8){
            url = route('records.store.signatures');
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
        let url = route('records.store.signatures');

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
                }else if(parentFieldset.index() == 2 || parentFieldset.index() == 3 || parentFieldset.index() == 4 || parentFieldset.index() == 5){
                    url = route('records.store.appliance');
                }else if(parentFieldset.index() == 6){
                    url = route('records.store.satisfactory.check');
                }else if(parentFieldset.index() == 7){
                    url = route('records.store.comments');
                }else if(parentFieldset.index() == 8){
                    url = route('records.store.signatures');
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
        }
    });

    //Appliance Type & Make On Change
    $('.applianceMake').on('change', function(){
        let $theMake = $(this);
        let $theFieldset = $theMake.parents('.wizard-fieldset');
        let fieldSetIndex = $theFieldset.index();

        let $theType = $theFieldset.find('.applianceType');

        let theMake = $theMake.val();
        let theMakeText = $('option:selected', $theMake).text();
        let theType = $theType.val();
        let theTypeText = $('option:selected', $theType).text();

        let theHtml = theMake > 0 ? theMakeText+' ' : '';
            theHtml += theType > 0 ? theTypeText : '';

        if(theHtml != ''){
            $theFieldset.find('.wizard-fieldset-header h2 span').html(theHtml);
            $('.form-wizard-steps .form-wizard-step-item').eq(fieldSetIndex).children('span').html(theHtml);
        }else{
            $theFieldset.find('.wizard-fieldset-header h2 span').html($theFieldset.attr('data-title'));
            $('.form-wizard-steps .form-wizard-step-item').eq(fieldSetIndex).children('span').html($('.form-wizard-steps .form-wizard-step-item').eq(fieldSetIndex).attr('data-title'));
        }
    });
    $('.applianceType').on('change', function(){
        let $theType = $(this);
        let $theFieldset = $theType.parents('.wizard-fieldset');
        let fieldSetIndex = $theFieldset.index();

        let $theMake = $theFieldset.find('.applianceMake');

        let theMake = $theMake.val();
        let theMakeText = $('option:selected', $theMake).text();
        let theType = $theType.val();
        let theTypeText = $('option:selected', $theType).text();

        let theHtml = theMake > 0 ? theMakeText+' ' : '';
            theHtml += theType > 0 ? theTypeText : '';

        $theFieldset.find('.wizard-fieldset-header h2 span').html(theHtml);
        $('.form-wizard-steps .form-wizard-step-item').eq(fieldSetIndex).children('span').html(theHtml);
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

    // Signature Toggle
    /*$('.gsfSignatureBtns .signBtns').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $('.gsfSignatureBtns .uploadBtns').removeClass('active');
        $theBtn.addClass('active');

        $('.gsfSignature .customeUploads').fadeOut('fast', function(){
            $('.gsfSignature .e-signpad').fadeIn();
            $('#signature_image').fadeOut('fast', function(){
                $('.customeUploads .customeUploadsContent').fadeIn();
                $('#signature_file').val('');
            })
        });
        
    })
    $('.gsfSignatureBtns .uploadBtns').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);

        $('.gsfSignatureBtns .signBtns').removeClass('active');
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
    };*/

})();