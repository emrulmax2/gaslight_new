@extends('../themes/base')

@section('head')
    <title>Gas Engineer App</title>
@endsection

@section('content')
    <div
        class="container grid grid-cols-12 px-5 py-10 sm:px-10 sm:py-14 md:px-36 lg:h-screen lg:max-w-[1550px] lg:py-0 lg:pl-14 lg:pr-12 xl:px-24 2xl:max-w-[1750px]">
        <div @class([
            'relative z-50 h-full col-span-12 p-7 sm:p-14 bg-white rounded-2xl lg:bg-transparent lg:pr-10 lg:col-span-5 xl:pr-24 2xl:col-span-4 lg:p-0',
            "before:content-[''] before:absolute before:inset-0 before:-mb-3.5 before:bg-white/40 before:rounded-2xl before:mx-5",
        ])>
            <div class="relative z-10 flex flex-col justify-center w-full h-full py-2 lg:py-32">
                <div class="flex h-[55px] w-[55px] items-center justify-center rounded-[0.8rem] border border-primary/30">
                    <div class="relative flex  items-center justify-center rounded-[0.6rem] bg-white bg-gradient-to-b from-theme-1/90 to-theme-2/90">
                        <img
                            class="rounded-[0.6rem]"
                            src="{{ Vite::asset('resources/images/logo_icon.png') }}"
                            alt="Gas Engineer APP"
                        />
                    </div>
                </div>
                <div class="mt-10">
                    <div class="text-2xl font-medium">Reset Password</div>
                    <div class="mt-2.5 text-slate-600">
                        Already have an account?
                        <a
                            class="font-medium text-primary"
                            href="{{ route('login') }}"
                        >
                            Sign In
                        </a>
                    </div>
                    
                    <form id="resetPasswordForm">
                        <div class="mt-6">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">

                                <div class="intro-y col-span-12 sm:col-span-6">
                                    <x-base.form-label>Password <span class="text-danger ml-2">*</span></x-base.form-label>
                                    <div class="relative">
                                        <x-base.form-input type="password" placeholder="************" name="password" id="password" />
                                        <span id="togglePasswordShow" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                                            <i id="togglePasswordIcon" data-lucide="eye-off"></i>
                                        </span>
                                    </div>
                                    <div id="password-strength" class="mt-3.5 grid h-1.5 w-full grid-cols-12 gap-2">
                                        <div id="strength-1" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                        <div id="strength-2" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                        <div id="strength-3" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                        <div id="strength-4" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                                    </div>
                                    <div class="acc__input-error error-password mt-2 text-danger text-left text-xs"></div>
                                </div>
                                <div class="intro-y col-span-12 sm:col-span-6">
                                    <x-base.form-label>Password Confirmation <span class="text-danger ml-2">*</span></x-base.form-label>
                                    <div class="relative">
                                        <x-base.form-input class="block" type="password" placeholder="************" name="password_confirmation" id="password_confirmation" />
                                        <span id="toggleConfirmPasswordShow" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" >
                                            <i id="togglePasswordConfirmationIcon" data-lucide="eye-off"></i>
                                        </span>
                                    </div>
                                    <div class="acc__input-error error-password_confirmation mt-2 text-danger text-left text-xs"></div>
                                </div>
                            
                            <div class="mt-5 text-center xl:mt-8 xl:text-left">
                                <x-base.button id="resetPassBtn" type="submit" 
                                    class="w-full bg-gradient-to-r from-theme-1/70 to-theme-2/70 py-3.5 xl:mr-3"
                                    variant="primary"
                                    rounded
                                >
                                    <span class="signin-text">Reset Password</span><x-base.loading-icon
                                    class="h-6 w-6 hidden theLoader"
                                    icon="oval" color="#fff"
                                />
                                </x-base.button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div
        class="container fixed inset-0 grid h-screen w-screen grid-cols-12 pl-14 pr-12 lg:max-w-[1550px] xl:px-24 2xl:max-w-[1750px]">
        <div @class([
            'relative h-screen col-span-12 lg:col-span-5 2xl:col-span-4 z-20',
            "after:bg-white after:hidden after:lg:block after:content-[''] after:absolute after:right-0 after:inset-y-0 after:bg-gradient-to-b after:from-white after:to-slate-100/80 after:w-[800%] after:rounded-[0_1.2rem_1.2rem_0/0_1.7rem_1.7rem_0]",
            "before:content-[''] before:hidden before:lg:block before:absolute before:right-0 before:inset-y-0 before:my-6 before:bg-gradient-to-b before:from-white/10 before:to-slate-50/10 before:bg-white/50 before:w-[800%] before:-mr-4 before:rounded-[0_1.2rem_1.2rem_0/0_1.7rem_1.7rem_0]",
        ])></div>
        <div @class([
            'h-full col-span-7 2xl:col-span-8 lg:relative',
            "before:content-[''] before:absolute before:lg:-ml-10 before:left-0 before:inset-y-0 before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 before:w-screen before:lg:w-[800%]",
            "after:content-[''] after:absolute after:inset-y-0 after:left-0 after:w-screen after:lg:w-[800%] after:bg-texture-white after:bg-fixed after:bg-center after:lg:bg-[25rem_-25rem] after:bg-no-repeat",
        ])>
            <div class="sticky top-0 z-10 flex-col justify-center hidden h-screen ml-16 lg:flex xl:ml-28 2xl:ml-36">
                <div class="text-[2.6rem] font-medium leading-[1.4] text-white xl:text-3xl xl:leading-[1.2]">
                    Your Complete Solution for Fast, Reliable Gas Certificates
                </div>
                <div class="text-[2.6rem] font-medium leading-[1.4] text-white xl:text-3xl xl:leading-[1.2]">
                    Keeping Your Work Safe and Simple.
                </div>
                <div class="mt-5 text-base leading-relaxed text-white/70 xl:text-lg">
                    Empowering gas engineers with an effortless certification process, our app lets you issue gas certificates anytime, anywhere. Simplify your workflow and stay on top of your jobs with fast, reliable, and compliant certification at your fingertips.
                </div>
                <div class="flex flex-col gap-3 mt-10 xl:flex-row xl:items-center">
                    <div class="flex items-center">
                        <div class="image-fit zoom-in h-9 w-9 2xl:h-11 2xl:w-11">
                            <x-base.tippy
                                class="rounded-full border-[3px] border-white/50"
                                src="{{ isset($users[0]['photo']) ? Vite::asset($users[0]['photo']) : Vite::asset('resources/images/placeholders/200x200.jpg') }}"
                                alt="Tailwise - Admin Dashboard Template"
                                as="img"
                                content="{{ isset($users[0]['name']) ? $users[0]['name'] : '' }}"
                            />
                        </div>
                        <div class="-ml-3 image-fit zoom-in h-9 w-9 2xl:h-11 2xl:w-11">
                            <x-base.tippy
                                class="rounded-full border-[3px] border-white/50"
                                src="{{ isset($users[1]['photo']) ? Vite::asset($users[1]['photo']) : Vite::asset('resources/images/placeholders/200x200.jpg') }}"
                                alt="Tailwise - Admin Dashboard Template"
                                as="img"
                                content="{{ isset($users[1]['name']) ? $users[1]['name'] : '' }}"
                            />
                        </div>
                        <div class="-ml-3 image-fit zoom-in h-9 w-9 2xl:h-11 2xl:w-11">
                            <x-base.tippy
                                class="rounded-full border-[3px] border-white/50"
                                src="{{ isset($users[0]['photo']) ? Vite::asset($users[0]['photo']) : Vite::asset('resources/images/placeholders/200x200.jpg') }}"
                                alt="Tailwise - Admin Dashboard Template"
                                as="img"
                                content="{{ isset($users[0]['name']) ? $users[0]['name'] : '' }}"
                            />
                        </div>
                        <div class="-ml-3 image-fit zoom-in h-9 w-9 2xl:h-11 2xl:w-11">
                            <x-base.tippy
                                class="rounded-full border-[3px] border-white/50"
                                src="{{ isset($users[1]['photo']) ? Vite::asset($users[1]['photo']) : Vite::asset('resources/images/placeholders/200x200.jpg') }}"
                                alt="Tailwise - Admin Dashboard Template"
                                as="img"
                                content="{{ isset($users[1]['name']) ? $users[1]['name'] : '' }}"
                            />
                        </div>
                    </div>
                    <div class="text-base text-white/70 xl:ml-2 2xl:ml-3">
                        Over 1k+ strong and growing! Your journey begins here.
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('app.action-modals')
@endsection



@pushOnce('vendors')
    @vite('resources/js/utils/helper.js')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/toastify.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/otp-login.js')
    <script type="module">
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


            $('#resetPasswordForm').on('submit', function(e){
                e.preventDefault();
                const form = document.getElementById('resetPasswordForm');
                const $theForm = $(this);
                
                $('#resetPasswordForm .acc__input-error').html('').removeClass('mt-1');
                $('#resetPassBtn', $theForm).attr('disabled', 'disabled');
                $("#resetPassBtn .theLoader").fadeIn();

                let form_data = new FormData(form);
                axios({
                    method: "post",
                    url: route('password.update'),
                    data: form_data,
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    $('#resetPassBtn', $theForm).removeAttr('disabled');
                    $("#resetPassBtn .theLoader").fadeOut();

                    if (response.status == 200) {
                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', response.data.red);
                        });

                        setTimeout(() => {
                            successModal.hide();
                            window.location.href = response.data.red;
                        }, 1500);
                    }
                }).catch(error => {
                    $('#resetPassBtn', $theForm).removeAttr('disabled');
                    $("#resetPassBtn .theLoader").fadeOut();
                    if (error.response) {
                        if (error.response.status == 422) {
                            for (const [key, val] of Object.entries(error.response.data.errors)) {
                                $(`#resetPasswordForm .${key}`).addClass('border-danger');
                                $(`#resetPasswordForm  .error-${key}`).html(val).addClass('mt-1');
                            }
                        } else if (error.response.status == 400) {
                            warningModal.show();
                            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                                $("#warningModal .warningModalTitle").html("Error Found!");
                                $("#warningModal .warningModalDesc").html(error.response.data.message);
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


            $('#password').on('input', function() {
                evaluatePasswordStrength()
            })
            $('#togglePasswordShow').on('click', function() {
                togglePasswordVisibility('password', 'togglePasswordIcon')
            })
            $('#toggleConfirmPasswordShow').on('click', function() {
                togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmationIcon')
            })



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
    </script>
@endPushOnce
