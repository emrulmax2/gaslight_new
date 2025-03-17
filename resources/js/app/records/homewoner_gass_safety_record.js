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
            url = route('records.store.co.alarms');
        }else if(parentFieldset.index() == 7){
            url = route('records.store.satisfactory.check');
        }else if(parentFieldset.index() == 8){
            url = route('records.store.comments');
        }else if(parentFieldset.index() == 9){
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
            let formid = $currentActiveForm.attr('id');
            const form = document.getElementById(formid);
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
                url = route('records.store.co.alarms');
            }else if(parentFieldset.index() == 7){
                url = route('records.store.satisfactory.check');
            }else if(parentFieldset.index() == 8){
                url = route('records.store.comments');
            }else if(parentFieldset.index() == 9){
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

        $theFieldset.find('.wizard-fieldset-header h2 span').html(theHtml);
        $('.form-wizard-steps .form-wizard-step-item').eq(fieldSetIndex).children('span').html(theHtml);
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

})();