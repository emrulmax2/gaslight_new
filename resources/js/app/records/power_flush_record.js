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
            url = route('records.gas.power.flush.store.checklist');
        }else if(parentFieldset.index() == 3){
            url = route('records.gas.power.flush.store.radiators');
        }else if(parentFieldset.index() == 4){
            url = route('records.gas.power.flush.store.signatures');
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
        let url = route('records.gas.power.flush.store.signatures');

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
                    url = route('records.gas.power.flush.store.checklist');
                }else if(parentFieldset.index() == 3){
                    url = route('records.gas.power.flush.store.radiators');
                }else if(parentFieldset.index() == 4){
                    url = route('records.gas.power.flush.store.signatures');
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

    /* Add Radiator Items */
    $('.gasappRadiatorsWrap').on('click', '.gasapp-accordion-button', function(e){
        e.preventDefault();
        var $theBtn = $(this);
        var $theAccordion = $theBtn.closest('.gasapp-accordion');
        var $theHeader = $theBtn.parent('.gasapp-accordion-header');
        var $theBody = $theHeader.siblings('.gasapp-accordion-collapse');

        if($theBtn.hasClass('gasapp-collapsed')){
            $theAccordion.find('.gasapp-accordion-button').addClass('gasapp-collapsed');
            $theAccordion.find('.gasapp-accordion-collapse').removeClass('gasapp-show').slideUp();

            $theBtn.removeClass('gasapp-collapsed');
            $theBody.addClass('gasapp-show').slideDown();
        }else{
            $theBtn.addClass('gasapp-collapsed');
            $theBody.removeClass('gasapp-show').slideUp();
        }
    });
    
    $('#addReadiatorBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        var $theAccordion = $('#gasapp-accordion-radiators');
        $theAccordion.find('.gasapp-accordion-button').addClass('gasapp-collapsed');
        $theAccordion.find('.gasapp-accordion-collapse').removeClass('gasapp-show').slideUp();

        let sl = 1;
        if($('#gasapp-accordion-radiators .gasapp-accordion-item').length > 0){
            let $theLastItem = $('#gasapp-accordion-radiators .gasapp-accordion-item').last();
            sl = ($theLastItem.attr('data-serial') ? ($theLastItem.attr('data-serial') * 1 + 1) : sl);
        }

        let html = '';
        html += '<div class="gasapp-accordion-item mb-2" data-serial="'+sl+'">';
            html += '<div id="gasapp-accr-radiators-content-'+sl+'" class="gasapp-accordion-header relative">';
                html += '<button class="gasapp-accordion-button relative bg-primary text-white text-[14px] capitalize w-full text-left font-medium px-5 py-4 [&amp;.gasapp-collapsed]:bg-slate-200 [&amp;.gasapp-collapsed]:text-primary" type="button">';
                    html += '<span class="radiatorTitle">('+sl+') Rediator</span>';
                    html += '<span class="accordionCollaps"></span>';
                html += '</button>';
                html += '<button data-id="0" type="button" style="right: 20px;" class="deleteRadiator absolute rounded-full top-0 bottom-0 my-auto bg-danger text-white w-[30px] h-[30px] inline-flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="trash-2" class="lucide lucide-trash-2 stroke-1.5 w-4 h-4 text-white"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg></button>';
            html += '</div>';
            html += '<div id="gasapp-accr-radiators-collapse-'+sl+'" class="gasapp-accordion-collapse gasapp-show">';
                html += '<div class="gasapp-accordion-body border border-slate-200 border-t-0 p-5">';
                    html += '<div class="grid grid-cols-12 gap-x-5 gap-y-3">';
                        html += '<div class="col-span-12">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Rediator Location</label>';
                            html += '<input value="" name="red['+sl+'][rediator_location]" type="text" placeholder="" class="reaiator_location_name disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';
                        html += '<div class="col-span-12">';
                            html += '<h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Temperature before powerflus in °C</h2>';
                        html += '</div>';
                        html += '<div class="col-span-12 sm:col-span-3">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Top</label>';
                            html += '<input value="" name="red['+sl+'][tmp_b_top]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';
                        html += '<div class="col-span-12 sm:col-span-3">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Bottom</label>';
                            html += '<input value="" name="red['+sl+'][tmp_b_bottom]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';
                        html += '<div class="col-span-12 sm:col-span-3">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Left</label>';
                            html += '<input value="" name="red['+sl+'][tmp_b_left]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';
                        html += '<div class="col-span-12 sm:col-span-3">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Right</label>';
                            html += '<input value="" name="red['+sl+'][tmp_b_right]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';

                        html += '<div class="col-span-12">';
                            html += '<h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Temperature After powerflus in °C</h2>';
                        html += '</div>';
                        html += '<div class="col-span-12 sm:col-span-3">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Top</label>';
                            html += '<input value="" name="red['+sl+'][tmp_a_top]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';
                        html += '<div class="col-span-12 sm:col-span-3">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Bottom</label>';
                            html += '<input value="" name="red['+sl+'][tmp_a_bottom]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';
                        html += '<div class="col-span-12 sm:col-span-3">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Left</label>';
                            html += '<input value="" name="red['+sl+'][tmp_a_left]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';
                        html += '<div class="col-span-12 sm:col-span-3">';
                            html += '<label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Right</label>';
                            html += '<input value="" name="red['+sl+'][tmp_a_right]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            html += '</div>';
        html += '</div>';

        $('.gasappRadiatorsNotice').fadeOut('fast', function(){
            $('.gasappRadiatorsAccordion').fadeIn('fast', function(){
                $('#gasapp-accordion-radiators').append(html);
            })
        })
    });

    $('.gasappRadiatorsWrap').on('keyup past', '.reaiator_location_name', function(){
        let $theInput = $(this);
        let $theAccordionItem = $theInput.closest('.gasapp-accordion-item'); 
        let $theBtn = $theAccordionItem.find('.gasapp-accordion-button');

        let theSerial = $theAccordionItem.attr('data-serial');
        let theVal = $theInput.val();

        let theHtml = (theVal != '' ? '('+theSerial+') Radiator ('+theVal+')' : '('+theSerial+') Radiator');
        $theBtn.find('.radiatorTitle').html(theHtml);
    })


    $('.gasappRadiatorsWrap').on('click', '.deleteRadiator', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let $theAccordionItem = $theBtn.closest('.gasapp-accordion-item');
        let row_id = $theBtn.attr('data-id');

        $('.gasappRadiatorsWrap').find('.gasapp-accordion-item').removeClass('activeForDelete');
        $theAccordionItem.addClass('activeForDelete');

        confirmModal.show();
        document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
            $('#confirmModal .confirmModalTitle').html('Are you sure?');
            $('#confirmModal .confirmModalDesc').html('Do you really want to delete this rediator? Click on agree to continue.');
            $('#confirmModal .agreeWith').attr('data-id', row_id);
            $('#confirmModal .agreeWith').attr('data-action', 'DELETERED');
        });
    });

    // Confirm Modal Action
    $('#confirmModal .agreeWith').on('click', function(){
        let $agreeBTN = $(this);
        let row_id = $agreeBTN.attr('data-id');
        let action = $agreeBTN.attr('data-action');
        let $theActiveRow = $('.gasappRadiatorsWrap').find('.gasapp-accordion-item.activeForDelete');

        $('#confirmModal button').attr('disabled', 'disabled');
        if(action == 'DELETERED'){
            if(row_id > 0){
                axios({
                    method: 'delete',
                    url: route('records.gas.power.flush.record.delete.rediator', row_id),
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    if (response.status == 200) {
                        $theActiveRow.remove();
                        $('#confirmModal button').removeAttr('disabled');
                        confirmModal.hide();
    
                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.msg);
                            $("#successModal .agreeWith").attr('data-action', 'NONE').attr('data-redirect', '');
                        });

                        setTimeout(function(){
                            successModal.hide();
                        }, 1500);
                    }
                }).catch(error =>{
                    console.log(error)
                });
            }else{
                $theActiveRow.remove();
                $('#confirmModal button').removeAttr('disabled');
                confirmModal.hide();
            }
        }
    });

})();