
(function(){

    $('#sendOtp').on('click', function(e){
        e.preventDefault();
        let $theForm = $('#otpLoginForm');
        let $theBtn = $(this);
        let theMobileNumber = $('#mobileNumber').val();

        $theBtn.attr('disabled', 'disabled');
        $theBtn.find('.login__loading').fadeIn();

        if(theMobileNumber.length == 11){
            var startWith = theMobileNumber.substr(0, 2);
            if(startWith == '07'){
                $theForm.find('.error-mobile').fadeOut().html('');

                $.ajax({
                    type: 'POST',
                    data: {mobile : theMobileNumber},
                    url: route('login.send.otp'),
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                    async: false,
                    success: function(data) {
                        $theForm.find('#uid').val(data.user_id);

                        $theForm.find('.error-mobile').fadeOut().html('');
                        $theBtn.removeAttr('disabled');
                        $theBtn.find('.login__loading').fadeOut();
                        $theForm.find('#mobileNumberWrap').fadeOut('fast', function(){
                            $theForm.find('#otpWrap').fadeIn();

                            countDownClock('countdown', 10);
                        })
                    },
                    error:function(jqXHR, textStatus, errorThrown){
                        const errorMessage = jqXHR.responseText;

                        $theForm.find('#uid').val(0);
                        $theBtn.removeAttr('disabled');
                        $theBtn.find('.login__loading').fadeOut();
                        $theForm.find('.error-mobile').fadeIn().html('Invalid mobile number given.');
                    }
                });
            }else{
                $theForm.find('.error-mobile').fadeIn().html('The number should began with 07.');
            }
        }else{
            $theForm.find('#uid').val(0);
            $theBtn.removeAttr('disabled');
            $theBtn.find('.login__loading').fadeOut();
            $theForm.find('.error-mobile').fadeIn().html('Please enter an 11 digit number.');
        }

        setTimeout(() => {
            $theForm.find('.error-mobile').fadeOut().html('');
        }, 3000);
    });

    $('#resendOtp').on('click', function(e){
        e.preventDefault();
        let $theLink = $(this);
        let $theForm = $('#otpLoginForm')
        $('.error-otp').html('');

        if(!$theLink.hasClass('processing')){
            $theLink.addClass('processing opacity-7');

            let $theMobileInput = $theForm.find('#mobileNumber');
            let theMobileNumber = $theMobileInput.val();

            $.ajax({
                type: 'POST',
                data: {mobile : theMobileNumber},
                url: route('login.send.otp'),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                async: false,
                success: function(data) {
                    $theForm.find('.acc__input-error.error-otp').fadeOut().html('')
                    $theForm.find('#uid').val(data.user_id);
                    $('#countDownHtml').fadeOut('fast', function(){
                        $('#resendOtp', this).removeClass('processing opacity-7');
                    });
                    countDownClock('countdown', 180);
                },
                error:function(e){
                    $theForm.find('#uid').val(0);
                    $theForm.find('.acc__input-error.error-otp').fadeIn().html('Try again later.')
                    $theLink.removeClass('processing opacity-7');
                    clearInterval(countDowns);
                    $('#countdown').fadeOut().html('');
                    console.log('Error');

                    setTimeout(() => {
                        $theForm.find('.acc__input-error.error-otp').fadeOut().html('');
                    }, 5000);
                }
            });
        }
    });

    $('#otpLoginForm').on('submit', function(e){
        e.preventDefault();
        let $theForm = $(this);
        let $theBtn = $theForm.find('#loginWithOtp');
        const form = document.getElementById('otpLoginForm');

        $theBtn.attr('disabled', 'disabled');
        $theBtn.find(".login__loading").fadeIn();

        let theOtp = '';
        $theForm.find('.otpCodes').each(function(e){
            theOtp += $(this).val();
        });
        
        if(theOtp == '' || theOtp.length != 4){
            $theForm.find('.acc__input-error.error-otp').fadeIn().html('Please enter a 4 digit OTP.');
            $theBtn.removeAttr('disabled');
            $theBtn.find(".login__loading").fadeOut();

            setTimeout(() => {
                $theForm.find('.acc__input-error.error-otp').fadeOut().html('');
            }, 5000);
        }else{
            let formData = new FormData(form);
            formData.append('otp', theOtp);
            axios({
                method: "post",
                url: route('login.otp.check'),
                data: formData,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(res => {
                $theBtn.find(".login__loading").fadeOut();
                location.href = route('company.dashboard');
            }).catch(error => {
                $theBtn.removeAttr('disabled');
                $theBtn.find(".login__loading").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#otpLoginForm .${key}`).addClass('border-danger');
                            $(`#otpLoginForm  .error-${key}`).html(val).addClass('mt-1');
                        }
                    } else if (error.response.status == 304) {
                        $theBtn.find(".login__loading").fadeOut();

                        setTimeout(() => {
                            $theForm.find('.acc__input-error.error-otp').fadeOut().html('');
                        }, 5000);
                    } else {
                        console.log('error');
                    }
                }
                setTimeout(() => {
                    $theForm.find('.acc__input-error.error-otp').fadeOut().html('');
                }, 5000);
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



    let countDowns;
    function countDownClock(element_id, timeLeft = 30) {
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

})();