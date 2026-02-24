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
                    <div class="text-2xl font-medium">Sign In</div>
                    <div class="mt-2.5 text-slate-600">
                        Don't have an account?
                        <a
                            class="font-medium text-primary"
                            href="{{ route('register.index') }}"
                        >
                            Sign Up
                        </a>
                    </div>
                    
                    <div class="mt-6">
                        <form id="login-form">
                            <x-base.form-label>Email*</x-base.form-label>
                            <x-base.form-input id="email"
                                class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5 login__input"
                                type="text"
                                placeholder="username@example.com"
                            />
                            <div id="error-email" class="login__input-error text-danger mt-2 dark:text-orange-400 "></div>        
                            <x-base.form-label class="mt-4">Password*</x-base.form-label>
                            <x-base.form-input id="password"
                                class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5"
                                type="password"
                                placeholder="************"
                            />
                            <div id="error-password" class="login__input-error text-danger mt-2 dark:text-orange-400"></div>
                        </form>
                        <div class="flex mt-4 text-xs text-slate-500 sm:text-sm">
                            <div class="flex items-center mr-auto">
                                <x-base.form-check.input
                                    class="mr-2.5 border"
                                    id="remember-me"
                                    type="checkbox"
                                />
                                <label
                                    class="cursor-pointer select-none"
                                    for="remember-me"
                                >
                                    Remember me
                                </label>
                            </div>
                            <a data-tw-toggle="modal" data-tw-target="#forgetPasswordModal" href="javascript:void(0);">Forgot Password?</a>
                        </div>
                        <div class="mt-5 text-center xl:mt-8 xl:text-left">
                            <x-base.button id="btn-login" 
                                class="w-full bg-gradient-to-r from-theme-1/70 to-theme-2/70 py-3.5 xl:mr-3"
                                variant="primary"
                                rounded
                            >
                                <span class="signin-text">Sign In</span><x-base.loading-icon
                                class="h-6 w-6 hidden login__loading"
                                icon="oval" color="#fff"
                            />
                            </x-base.button>
                            <!--<a href="{{ route('register') }}"
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center px-3 font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 rounded-full mt-3 w-full bg-white/70 py-3.5"
                                variant="outline-secondary"
                                rounded
                            >
                                Sign Up
                            </a>-->
                        </div>
                        
                        <div class="border-t border-slate-200 h-[1px] relative mt-7 mb-7">
                            <span class="font-medium leading-none italic absolute left-0 right-0 mx-auto w-[35px] top-[-6px] bg-white text-center">OR</span>
                        </div>
                        <x-base.button as="a" href="{{ route('login') }}" class="w-full" variant="outline-success">
                            <x-base.lucide class="w-4 h-4 mr-2" icon="smartphone" />
                            <span class="signin-text">Continue with Mobile</span>
                        </x-base.button>
                    </div>
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
    {{-- <ThemeSwitcher /> --}}
    @if ($errors->any() || session('success'))
        <!-- BEGIN: Notification Content -->
        <x-base.notification
            class="flex"
            id="success-notification-content"
        >
            <x-base.lucide
                class="text-warning"
                icon="CheckCircle"
            />
            <div class="ml-4 mr-4">
                @if($errors->any())
                <div class="font-medium">Error!!</div>
                <div class="mt-1 text-slate-500">
                    <ul class="max-w-md space-y-1 text-gray-500 list-none list-inside dark:text-gray-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
                @else
                <div class="font-medium">Success!!</div>
                <div class="mt-1 text-slate-500">
                    {{ session('success') }}
                </div>  
                @endif
            </div>
        </x-base.notification>
        <!-- END: Notification Content -->
        <!-- BEGIN: Notification Toggle -->
        <x-base.button
            id="success-notification-toggle"
            variant="primary" 
            class="hidden"
        >
            Show Notification
        </x-base.button>
        <!-- END: Notification Toggle -->
    @endif        

    <!-- BEGIN: SEND SMS Invitation Modal Content -->
    <x-base.dialog id="forgetPasswordModal" staticBackdrop>
        <x-base.dialog.panel>
            <form method="post" action="#" id="forgetPasswordForm">
                <x-base.dialog.title>
                    <h2 class="mr-auto text-base font-medium">Forgot Password</h2>
                    <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
                </x-base.dialog.title>
                <x-base.dialog.description >
                    <div class="mb-3">
                        <x-base.form-label for="email">Email <span class="text-danger">*</span></x-base.form-label>
                        <x-base.form-input name="email" id="email" class="w-full" type="email" placeholder="emp@example.com" />
                        <div class="acc__input-error error-email text-danger text-xs"></div>
                    </div>
                </x-base.dialog.description>
                <x-base.dialog.footer>
                    <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                    <x-base.button class="w-auto" id="sendLinkBtn" type="submit" variant="primary">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Send Link
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                </x-base.dialog.footer>
            </form>
        </x-base.dialog.panel>
    </x-base.dialog>
    <!-- END: SEND SMS Invitation Modal Content -->
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
            const forgetPasswordModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#forgetPasswordModal"));
            
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

            
            if($('#success-notification-toggle').length>0) {

                $("#success-notification-toggle").on("click", function () {
                    // Init toastify
                    Toastify({
                    node: $("#success-notification-content")
                    .clone()
                    .removeClass("hidden")[0],
                    duration: -1,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    }).showToast();
                    });

                $("#success-notification-toggle").trigger('click')            
            }

            
            async function login() {
                // Reset state
                $('#login-form').find('.login__input').removeClass('border-danger')
                $('#login-form').find('.login__input-error').html('')

                // Post form
                let email = $('#email').val()
                let password = $('#password').val()
                $('.signin-text').addClass('hidden');
                $('#btn-login .login__loading').removeClass('hidden');
                // Loading state
                //$('#btn-login').html('<i data-loading-icon="oval" data-color="white" class="w-5 h-5 mx-auto"></i>')
                //tailwind.svgLoader()
                await helper.delay(1500)

                axios.post(route('login.check'), {
                    email: email,
                    password: password
                }, { 
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                    withCredentials: true
                }).then(res => {
                    location.href = route('company.dashboard');
                }).catch(err => {
                    //$('#btn-login').html('Login')
                    $('#btn-login .login__loading').addClass('hidden');
                    $('.signin-text').removeClass('hidden');
                    if (err.response.data.message != 'Wrong email or password.') {
                        for (const [key, val] of Object.entries(err.response.data.errors)) {
                            $(`#${key}`).addClass('border-danger')
                            $(`#error-${key}`).html(val)
                        }
                    } else {
                        $(`#password`).addClass('border-danger')
                        $(`#error-password`).html(err.response.data.message)
                    }
                })
            }

            $('#login-form').on('keyup', function(e) {
                if (e.keyCode === 13) {
                    login()
                }
            })

            $('#btn-login').on('click', function() {
                login()
            })

            $('#forgetPasswordForm').on('submit', function(e){
                e.preventDefault();
                const form = document.getElementById('forgetPasswordForm');
                const $theForm = $(this);
                
                $('#forgetPasswordForm .acc__input-error').html('').removeClass('mt-1');
                $('#sendLinkBtn', $theForm).attr('disabled', 'disabled');
                $("#sendLinkBtn .theLoader").fadeIn();

                let form_data = new FormData(form);
                axios({
                    method: "post",
                    url: route('forgot.password.email'),
                    data: form_data,
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    $('#sendLinkBtn', $theForm).removeAttr('disabled');
                    $("#sendLinkBtn .theLoader").fadeOut();

                    if (response.status == 200) {
                        forgetPasswordModal.hide();
                        
                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', '');
                        });

                        setTimeout(() => {
                            successModal.hide();
                            window.location.reload();
                        }, 1500);
                    }
                }).catch(error => {
                    $('#sendLinkBtn', $theForm).removeAttr('disabled');
                    $("#sendLinkBtn .theLoader").fadeOut();
                    if (error.response) {
                        if (error.response.status == 422) {
                            for (const [key, val] of Object.entries(error.response.data.errors)) {
                                $(`#forgetPasswordForm .${key}`).addClass('border-danger');
                                $(`#forgetPasswordForm  .error-${key}`).html(val).addClass('mt-1');
                            }
                        } else if (error.response.status == 304) {
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
        })()
    </script>
@endPushOnce
