import { route } from 'ziggy-js';

("use strict");
(function () { 

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
            url: route('company.update.staff'),
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
                    $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
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

    $('#password').on('input', function() {
        evaluatePasswordStrength()
    })

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
    function evaluateUpdatedPasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthBars = document.querySelectorAll('#password-strength > div');
        let strength = 0;

        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        strengthBars.forEach((bar, index) => {
            if (index < strength) {
                bar.classList.add('bg-theme-1/30');
                bar.classList.remove('bg-slate-400/30');
            } else {
                bar.classList.add('bg-slate-400/30');
                bar.classList.remove('bg-theme-1/30');
            }
        });
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

})();