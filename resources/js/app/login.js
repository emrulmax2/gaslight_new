

(function(){
    $('.otpCodes').on('paste', function (e) {
        let pasteData = (e.originalEvent || e).clipboardData.getData('text'); 
        pasteData = pasteData.replace(/\D/g, '').substring(0, 6);
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


    $('#quickLoginBtn').on('click', function(e){
        e.preventDefault();
        let $theBtn = $(this);
        let theEmail = $('#quickEmail').val();

        $theBtn.attr('disabled', 'disabled');
        $theBtn.find('.theLoader').fadeIn();

        if(theEmail == ''){
            $theBtn.attr('disabled', 'disabled');
            $theBtn.find('.theLoader').fadeIn();
            $('.error-email').removeClass('hidden').html('Please insert a valid email address.');

            setTimeout(() => {
                $('.error-email').addClass('hidden').html('');
            }, 2500);
        }else{
            axios({
                method: "post",
                url: route('login.send.email.otp'),
                data: {email : theEmail},
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                $theBtn.removeAttr('disabled');
                $theBtn.find('.theLoader').fadeOut();
                if (response.status == 200) {
                    $('#quickLoginForm .quickLoginFirstStep').fadeOut('fast', function(){
                        $('#quickLoginForm .quickLoginLastStep').fadeIn('fast', function(){
                            $('input', this).val();
                        })
                    })
                }
            }).catch(error => {
                $theBtn.removeAttr('disabled');
                $theBtn.find('.theLoader').fadeOut();
                console.log(error.response)
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#quickLoginForm .quickLoginFirstStep .${key}`).addClass('border-danger');
                            $(`#quickLoginForm .quickLoginFirstStep  .error-${key}`).html(val);
                        }
                    } else if (error.response.status == 404 || error.response.status == 429) {
                        $('#quickLoginForm .quickLoginFirstStep').prepend('<div role="alert" class="otpError alert relative border rounded-md px-3 py-2 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-5 flex items-center w-full"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg>'+error.response.data.message+'</div>')

                        setTimeout(() => {
                            $('#quickLoginForm .quickLoginFirstStep .otpError').remove();
                        }, 2500);
                    } else {
                        console.log('error');
                    }

                    setTimeout(() => {
                        $(`#quickLoginForm .quickLoginFirstStep .acc__input-error`).html('');
                    }, 5000);
                }
            });
        }
    })

    //location.href = route('company.dashboard');
    $('#quickLoginForm').on('submit', function(e){
        e.preventDefault();
        let $theForm = $(this);
        let $theBtn = $theForm.find('#quickVerifyLoginBtn');
        const form = document.getElementById('quickLoginForm');

        $theBtn.attr('disabled', 'disabled');
        $theBtn.find(".theLoader").fadeIn();

        let theOtp = '';
        $theForm.find('.otpCodes').each(function(e){
            theOtp += $(this).val();
        });
        
        if(theOtp == '' || theOtp.length != 6){
            $theForm.find('.acc__input-error.error-otp').fadeIn().html('Please enter a 6 digit OTP.');
            $theBtn.removeAttr('disabled');
            $theBtn.find(".theLoader").fadeOut();

            setTimeout(() => {
                $theForm.find('.acc__input-error.error-otp').fadeOut().html('');
            }, 5000);
        }else{
            let formData = new FormData(form);
            formData.append('otp', theOtp);
            axios({
                method: "post",
                url: route('login.quick.login'),
                data: formData,
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(res => {
                $theBtn.removeAttr('disabled');
                $theBtn.find(".theLoader").fadeOut();
                location.href = route('company.dashboard');
            }).catch(error => {
                $theBtn.removeAttr('disabled');
                $theBtn.find(".theLoader").fadeOut();
                if (error.response) {
                    if (error.response.status == 422) {
                        for (const [key, val] of Object.entries(error.response.data.errors)) {
                            $(`#quickLoginForm .${key}`).addClass('border-danger');
                            $(`#quickLoginForm  .error-${key}`).html(val).addClass('mt-1');
                        }
                    } else if (error.response.status == 404 || error.response.status == 410 || error.response.status == 429 || error.response.status == 401) {

                        $('#quickLoginForm .quickLoginLastStep').prepend('<div role="alert" class="otpError alert relative border rounded-md px-3 py-2 bg-danger border-danger bg-opacity-20 border-opacity-5 text-danger dark:border-danger dark:border-opacity-20 mb-5 flex items-center w-full"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg>'+error.response.data.message+'</div>')

                        setTimeout(() => {
                            $('#quickLoginForm .quickLoginLastStep .otpError').remove();
                        }, 2500);
                    }else {
                        console.log('error');
                    }
                }
            });
        }
    })
})()