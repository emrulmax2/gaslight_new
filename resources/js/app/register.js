import { initGetAddressAutocomplete } from "../getAddressAutocomplete.js";

(function(){
    // INIT Address Lookup
    document.addEventListener('DOMContentLoaded', () => {
        initGetAddressAutocomplete({
            token: import.meta.env.VITE_GETADDRESS_API_KEY
        });
    });



    $('#mobileNumber').on('keyup paste', function(){
        let $theMobileInput = $(this);
        let theName = $theMobileInput.attr('name');
        let theMobileNumber = $theMobileInput.val();
        let $theStep = $('#stepMobileNumber');

        if(theMobileNumber.length == 11){
            $theStep.find('.form-wizard-next-btn').removeAttr('disabled');
        }else{
            $theStep.find('.form-wizard-next-btn').attr('disabled', 'disabled');
        }
    })

    $('#resendOtp').on('click', function(e){
        e.preventDefault();
        let $theLink = $(this);
        $('.error-otp').html('');

        if(!$theLink.hasClass('processing')){
            $theLink.addClass('processing opacity-7');

            let $theMobileInput = $('#mobileNumber');
            let theName = $theMobileInput.attr('name');
            let theMobileNumber = $theMobileInput.val();

            $.ajax({
                type: 'POST',
                data: {MobileNumber : theMobileNumber},
                url: route('register.generate.otp'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                success: function(data) {
                    $('#countDownHtml').fadeOut('fast', function(){
                        $('#resendOtp', this).removeClass('processing opacity-7');
                    });
                    countDownClock('countdown', 180);
                },
                error:function(e){
                    $('.error-otp').html('Try again later.')
                    clearInterval(countDowns);
                    $('#countdown').fadeOut().html('');
                    console.log('Error');

                    setTimeout(() => {
                        $('.error-otp').html('')
                    }, 5000);
                }
            });
        }
    })

    $('.otpCodes').on('paste', function (e) {
        let pasteData = (e.originalEvent || e).clipboardData.getData('text'); 
        pasteData = pasteData.replace(/\D/g, '').substring(0, 4);
        $('.otpCodes').each(function (i) {
            $(this).val(pasteData[i] || '');
        });

        e.preventDefault();
    });

    $('.otpCodes').on('input', function () {
        if (this.value.length === this.maxLength) {
            $(this).next('.otpCodes').focus();
        }
    });

    $('.form-wizard-next-btn').on('click', function (e) {
        e.preventDefault();
        var parentFieldset = $(this).parents('.wizard-fieldset');
        let the_id = parentFieldset.attr('id');
        let $theStep = $('#'+the_id);
        var next = $(this);
        let nextWizardStep = true;

        
        next.attr('disabled', 'disabled');
        $('.theLoader', next).fadeIn();
        if(the_id == 'stepMobileNumber'){
            let $theMobileInput = $('#mobileNumber');
            let theName = $theMobileInput.attr('name');
            let theMobileNumber = $theMobileInput.val();

            if(theMobileNumber.length == 11){
                var startWith = theMobileNumber.substr(0, 2);
                if(startWith == '07'){
                    $theStep.find('.error-mobile').fadeOut().html('');

                    $.ajax({
                        type: 'POST',
                        data: {MobileNumber : theMobileNumber},
                        url: route('register.generate.otp'),
                        headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                        async: false,
                        success: function(data) {
                            next.removeAttr('disabled');
                            next.find('.theLoader').fadeOut();

                            $theStep.find('.error-mobile').fadeOut().html('');
                            $theStep.find('.otpCodes').val('');
                            $('#stepVerifiedOtp .mobileNumberShow').text('+44('+theMobileNumber.substr(0, 1)+')'+theMobileNumber.substr(1, 10));
                            $('#countDownHtml').fadeOut('fast', function(){
                                $('#resendOtp', this).removeClass('processing opacity-7');
                            });
                            countDownClock('countdown', 180);
                            nextWizardStep = true;
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            var responseData = JSON.parse(jqXHR.responseText);
                            $theStep.find('.error-mobile').fadeIn().html(responseData.message);
                            
                            next.removeAttr('disabled');
                            next.find('.theLoader').fadeOut();

                            clearInterval(countDowns);
                            $('#countdown').fadeOut().html('');
                            $theStep.find('.otpCodes').val('');
                            nextWizardStep = false;
                            console.log('Error');
                        }
                    });
                }else{
                    next.removeAttr('disabled');
                    next.find('.theLoader').fadeOut();

                    $theStep.find('.error-mobile').fadeIn().html('The number should began with 07.');
                    nextWizardStep = false;
                }
            }else{
                next.removeAttr('disabled');
                next.find('.theLoader').fadeOut();

                $theStep.find('.form-wizard-next-btn').attr('disabled', 'disabled');
                $theStep.find('.error-mobile').fadeOut().html('Please enter an 11 digit number.');
                nextWizardStep = false;
            }
        }else if(the_id == 'stepVerifiedOtp'){
            let theOtp = '';
            $theStep.find('.otpCodes').each(function(e){
                theOtp += $(this).val();
            });
            //console.log(theOtp);

            if(theOtp.length == 4){
                let $theMobileInput = $('#mobileNumber');
                let theMobileNumber = $theMobileInput.val();
                $.ajax({
                    type: 'POST',
                    data: {MobileNumber : theMobileNumber, theOtp : theOtp},
                    url: route('register.validate.otp'),
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                    async: false,
                    success: function(data) {
                        next.removeAttr('disabled');
                        next.find('.theLoader').fadeOut();

                        $theStep.find('.otpCodes').val('');
                        nextWizardStep = true;
                    },
                    error:function(e){
                        next.removeAttr('disabled');
                        next.find('.theLoader').fadeOut();

                        $theStep.find('.error-otp').html('OTP does not match.');
                        nextWizardStep = false;
                        console.log('Error');
                    }
                });
            }else{
                next.removeAttr('disabled');
                next.find('.theLoader').fadeOut();

                $theStep.find('.error-otp').html('Please enter a 4 digit OTP.');
                nextWizardStep = false;
            }

            setTimeout(() => {
                $('.error-otp').html('')
            }, 5000);
        }else if(the_id == 'stepBusinessInfo'){
            let theSetpError = 0;

            $($theStep).find('require').each(function(e){
                let theInputName = $(this).attr('name');
                let $theField = $(this);
                if(theInputName == 'business_type'){
                    if($theField.val() == ''){
                        $theStep.find('.acc__input-error.error-'+theInputName).html('This field is required.');
                        theSetpError += 1;
                    }else{
                        $theStep.find('.acc__input-error.error-'+theInputName).html('');
                        if($theField.val() == 'Company'){
                            if($theStep.find('#company_registration').val() == ''){
                                $theStep.find('.acc__input-error.error-company_registration').html('This field is required.');
                                theSetpError += 1;
                            }else{
                                $theStep.find('.acc__input-error.error-company_registration').html('');
                            }
                        }
                    }
                }else{
                    if($theField.val() == ''){
                        $theStep.find('.acc__input-error.error-'+theInputName).html('This field is required.');
                        theSetpError += 1;
                    }else{
                        $theStep.find('.acc__input-error.error-'+theInputName).html('');
                    }
                }
            });

            if($theStep.find('#vat_number_check').prop('checked')){
                if($theStep.find('#vat_number').val() == ''){
                    $theStep.find('.acc__input-error.error-vat_number').html('This field is required.');
                    theSetpError += 1;
                }else{
                    $theStep.find('.acc__input-error.error-vat_number').html('');
                }
            }else{
                $theStep.find('.acc__input-error.error-vat_number').html('');
            }

            if($theStep.find('#password').val() != '' && $theStep.find('#password_confirmation').val() != ''){
                if($theStep.find('#password').val() != $theStep.find('#password_confirmation').val()){
                    $theStep.find('.acc__input-error.error-password_confirmation').html('Password does not match.');
                    theSetpError += 1;
                }else{
                    $theStep.find('.acc__input-error.error-password_confirmation').html('');
                }
            }else{
                if($theStep.find('#password').val() == ''){
                    $theStep.find('.acc__input-error.error-password').html('This field is required');
                    theSetpError += 1;
                }else{
                    $theStep.find('.acc__input-error.error-password').html('');
                }
                if($theStep.find('#password_confirmation').val() == ''){
                    $theStep.find('.acc__input-error.error-password_confirmation').html('This field is required');
                    theSetpError += 1;
                }else{
                    $theStep.find('.acc__input-error.error-password_confirmation').html('');
                }
            }

            if($theStep.find('#email').val() != ''){
                let the_email = $theStep.find('#email').val()
                $.ajax({
                    type: 'POST',
                    data: {email : the_email},
                    url: route('register.validate.email'),
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                    async: false,
                    success: function(data) {

                        $theStep.find('.acc__input-error.error-email').html('');
                    },
                    error:function(e){

                        $theStep.find('.acc__input-error.error-email').html('Email id already exist.');
                        theSetpError += 1;
                        console.log('Error');
                    }
                });
            }

            if(theSetpError > 0){
                nextWizardStep = false;
            }
            next.removeAttr('disabled');
            next.find('.theLoader').fadeOut();
        }else if(the_id == 'stepContactDetails'){
            let theSetpError = 0;

            $($theStep).find('.require').each(function(e){
                let theInputName = $(this).attr('name');
                let $theField = $(this);
                
                if($theField.val() == ''){
                    $theStep.find('.acc__input-error.error-'+theInputName).html('This field is required.');
                    theSetpError += 1;
                }else{
                    $theStep.find('.acc__input-error.error-'+theInputName).html('');
                }
            });

            if(theSetpError > 0){
                nextWizardStep = false;
            }
            next.removeAttr('disabled');
            next.find('.theLoader').fadeOut();
        }else if(the_id == 'stepOtherInfo'){
            let theSetpError = 0;

            $($theStep).find('.require').each(function(e){
                let theInputName = $(this).attr('name');
                let $theField = $(this);
                
                if($theField.val() == ''){
                    $theStep.find('.acc__input-error.error-'+theInputName).html('This field is required.');
                    theSetpError += 1;
                }else{
                    $theStep.find('.acc__input-error.error-'+theInputName).html('');
                }
            });

            if($theStep.find('#referral_code').val() != ''){
                let referral_code = $theStep.find('#referral_code').val();
                $.ajax({
                    type: 'POST',
                    data: {referral_code : referral_code},
                    url: route('register.validate.referral'),
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                    async: false,
                    success: function(data) {
                        if(data.suc == 1){
                            $theStep.find('.acc__input-error.error-referral_code').html('');
                        }else{
                            $theStep.find('.acc__input-error.error-referral_code').html('Invalid referral code.');
                            theSetpError += 1;
                        }
                    },
                    error:function(e){
                        $theStep.find('.acc__input-error.error-referral_code').html('Try again later.');
                        theSetpError += 1;
                        console.log('Error');
                    }
                });
            }

            if(theSetpError > 0){
                nextWizardStep = false;
            }
            next.removeAttr('disabled');
            next.find('.theLoader').fadeOut();
        }
         
        if (nextWizardStep) {
            next.parents('.wizard-fieldset').removeClass("show");
            next.parents('.wizard-fieldset').next('.wizard-fieldset').addClass("show");
        }
    });

    $('.form-wizard-previous-btn').on('click', function () {
        var prev = $(this);
        let the_id = prev.parents('.wizard-fieldset').attr('id');
        let $theStep = prev.parents('.wizard-fieldset');
        prev.parents('.wizard-fieldset').removeClass("show");
        prev.parents('.wizard-fieldset').prev('.wizard-fieldset').addClass("show");

        if(the_id == 'stepVerifiedOtp'){
            $('#countDownHtml').fadeOut('fast', function(){
                $('#resendOtp', this).removeClass('processing opacity-7');
            });
            clearInterval(countDowns);
            $('#countdown').fadeOut().html('');
            $theStep.find('.otpCodes').val('');
        }else if(the_id == 'stepBusinessInfo'){
            $('#countDownHtml').fadeIn('fast', function(){
                $('#resendOtp', this).removeClass('processing opacity-7');
            });
            clearInterval(countDowns);
            $('#countdown').fadeOut().html('');
        }else if(the_id == 'stepSignature'){
            $("#registerSuccess").fadeOut(); 
        }
    });

    $('#vat_number_check').on('change', function () {
        if ($(this).prop('checked')) {
            $('.vatNumberInput').fadeIn('fast', function(){
                $('input', this).val('');
            })
        } else {
            $('.vatNumberInput').fadeOut('fast', function(){
                $('input', this).val('');
            })
        }
    })

    $('#business_type').on('change', function(){
        console.log('changed');
        if($(this).val() == 'Company'){
            $('.registrationWrap').fadeIn('fast', function(){
                $('input', this).val('');
            });
        }else{
            $('.registrationWrap').fadeOut('fast', function(){
                $('input', this).val('');
            });
        }
    });

    $('#password').on('input', function() {
        evaluatePasswordStrength()
    })
    $('#togglePasswordShow').on('click', function() {
        togglePasswordVisibility('password', 'togglePasswordIcon')
    })
    $('#toggleConfirmPasswordShow').on('click', function() {
        togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmationIcon')
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


    $('#submitTheFormWithSignature').on('click', function(e){
        e.preventDefault();
        $('#userRegistrationForm .gsfSignature .sign-pad-button-submit').trigger('click');
    });

    $('#userRegistrationForm').on('submit', function(e){
        e.preventDefault();
        const form = document.getElementById('userRegistrationForm');
        const $theForm = $(this);
        let $theBtn = $theForm.find('#submitTheFormWithSignature');
        
        $theBtn.siblings('.form-wizard-previous-btn').attr('disabled', 'disabled');
        $theBtn.attr('disabled', 'disabled');
        $theBtn.find(".theLoader").fadeIn();

        let theSign = $theForm.find('.sign').val();
        let theSignImage = $theForm.find('#signature_file');
        
        if(theSign.length <= 4358 && $('#signature_file').get(0).files.length === 0 ){
            $theForm.find('.acc__input-error.error-signature').html('Signature can not be empty.');
            $theBtn.siblings('.form-wizard-previous-btn').removeAttr('disabled');
            $theBtn.removeAttr('disabled', 'disabled');
            $theBtn.find(".theLoader").fadeOut();

            setTimeout(() => {
                $theForm.find('.acc__input-error.error-signature').html('');
            }, 5000);
        }else{
            let formData = new FormData(form);
            axios({
                method: "post",
                url: route('register'),
                data: formData,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(res => {
                //$theBtn.removeAttr('disabled', 'disabled');
                $theBtn.find(".theLoader").fadeOut();

                $("#registerSuccess").fadeIn();

                setTimeout(() => {
                    location.href = route('login');
                }, 2000);
            }).catch(err => {
                $("#registerSuccess").fadeOut();
                $theBtn.siblings('.form-wizard-previous-btn').removeAttr('disabled');
                $theBtn.removeAttr('disabled', 'disabled');
                $theBtn.find(".theLoader").fadeOut();

                if (err.response && err.response.data.errors) {
                    for (const [key, val] of Object.entries(err.response.data.errors)) {
                        $(`#${key}`).addClass('border-danger')
                        $(`#error-${key}`).html(val)
                    }
                } else if (err.response && err.response.data.error) {
                    Toastify({
                        text: err.response.data.error,
                        duration: 3000,
                        close: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: "left", // `left`, `center` or `right`
                        backgroundColor: "#FF0000",
                    }).showToast();
                }
            });
        }
    });

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



    let countDowns;
    function countDownClock(element_id, timeLeft = 180) {
        let $element = $('#'+element_id);
        $element.fadeIn().html('');
        countDowns = setInterval(function() { // removed var keyword
            if (timeLeft == 0) {
                clearInterval(countDowns);
                $element.fadeOut().html('');
                $('#countDownHtml').fadeIn('fast', function(){
                    $('#resendOtp', this).removeClass('processing opacity-7');
                });
            } else {
                var m = Math.floor(timeLeft / 60);
                var s = timeLeft % 60;
                    m = m < 10 ? '0' + m : m;
                    s = s < 10 ? '0' + s : s;
                $element.html(m+':'+s);
            }
            timeLeft -= 1;
        }, 1000);

    }


    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.setAttribute('data-lucide', 'eye');
        } else {
            passwordInput.type = 'password';
            icon.setAttribute('data-lucide', 'eye-off');
        }
        lucide.createIcons();
    }

    function evaluatePasswordStrength() {
        const password = document.getElementById('password').value;
        let strenghts = checkPasswordStrength(password);

        const box1 = document.getElementById('strength-1');
        const box2 = document.getElementById('strength-2');
        const box3 = document.getElementById('strength-3');
        const box4 = document.getElementById('strength-4');

        switch (strenghts) {
                case 1:
                        box1.classList.remove('border-slate-400/20', 'bg-slate-400/30')
                        box1.classList.add('bg-danger', 'border-danger');
                        break;
                case 2: 
                        box2.classList.remove('border-slate-400/20', 'bg-slate-400/30')
                        box2.classList.add('bg-warning', 'border-warning');
                        break;
                case 3: 
                        box3.classList.remove('border-slate-400/20', 'bg-slate-400/30')
                        box3.classList.add('bg-pending', 'border-pending');
                        break;
                case 4: 
                case 5: 
                case 6: 
                case 7: 
                case 8: 
                case 9: 
                        box4.classList.remove('border-slate-400/20', 'bg-slate-400/30')
                        box4.classList.add('bg-success', 'border-success');
                        break;
                default:
                        box1.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
                        box2.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
                        box3.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
                        box4.classList.remove('bg-danger', 'bg-warning','bg-success','bg-pending','border-danger','border-warning','border-pending','border-success');
                        
                        box1.classList.add('border-slate-400/20', 'bg-slate-400/30');
                        box2.classList.add('border-slate-400/20', 'bg-slate-400/30');
                        box3.classList.add('border-slate-400/20', 'bg-slate-400/30');
                        box4.classList.add('border-slate-400/20', 'bg-slate-400/30');
                        break;
        }
    }

    function checkPasswordStrength(password) {
        // Initialize variables
        let strength = 0;
        let tips = "";

        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
            strength += 1;
        } else {}

        //If it has numbers and characters
        if (password.match(/([0-9])/)) {
            strength += 1;
        } else {}

        //If it has one special character
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
            strength += 1;
        } else {}

        //If password is greater than 7
        if (password.length > 7) {
            strength += 1;
        } else {}
        
        // Return results
        if (strength < 2) {
            return strength;
        } else if (strength === 2) {
            return strength;
        } else if (strength === 3) {
            return strength;
        } else {
            return strength;
        }
    }
})()