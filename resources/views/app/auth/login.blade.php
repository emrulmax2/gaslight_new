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
                        <form id="otpLoginForm" action="#" method="post">
                            <input type="hidden" name="user_id" id="uid" value="0"/>
                            <div id="mobileNumberWrap">
                                <div class="mobileNumberInput">
                                    <x-base.form-label>Mobile Number <span class="text-danger">*</span></x-base.form-label>
                                    <x-base.input-group inputGroup>
                                        <x-base.input-group.text id="input-group-email" class="inline-flex items-center pr-5">
                                            <img src="{{ Vite::asset('resources/images/flags/uk.svg') }}" class="w-5 h-auto mr-2" alt="UK Flag"/>
                                            +44
                                        </x-base.input-group.text>
                                        <x-base.form-input type="number" id="mobileNumber" name="mobile" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="07123456789" />
                                    </x-base.input-group>
                                    <div class="acc__input-error error-mobile mt-2 text-danger text-xs" style="display: none;"></div>   
                                </div>
                                <div class="mt-5">
                                    <x-base.button type="button" id="sendOtp" class="w-full" variant="primary">
                                        <span class="signin-text">Send OTP</span>
                                        <x-base.loading-icon class="h-4 w-4 ml-2 hidden login__loading" icon="oval" color="#fff"/>
                                    </x-base.button>
                                </div>
                            </div>
                            <div id="otpWrap" style="display: none;">
                                <div class="otpInput">
                                    <div id="countdown" class="font-medium text-center text-success leading-none mb-3" style="display: none;">03:00</div>
                                    <div class="flex justify-center items-center">
                                        <x-base.form-input id="otp1" name="otp_1" type="number" class="w-[35px] mx-1 text-center px-0 otpCodes font-bold" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1" />
                                        <x-base.form-input id="otp2" name="otp_2" type="number" class="w-[35px] mx-1 text-center px-0 otpCodes font-bold" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1" />
                                        <x-base.form-input id="otp3" name="otp_3" type="number" class="w-[35px] mx-1 text-center px-0 otpCodes font-bold" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1" />
                                        <x-base.form-input id="otp4" name="otp_4" type="number" class="w-[35px] mx-1 text-center px-0 otpCodes font-bold" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1" />
                                    </div>
                                    <div class="acc__input-error error-otp mt-2 text-danger text-center text-xs" style="display: none;"></div>
                                    <div id="countDownHtml" class="text-center leading-none mt-5" style="display: none;">
                                        Don't received the OTP? <a href="javascript:void(0);" class="font-medium text-primary underline" id="resendOtp">RESEND</a>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <x-base.button type="submit" id="loginWithOtp" class="w-full text-white" variant="success">
                                        <span class="signin-text">Login</span>
                                        <x-base.loading-icon class="h-4 w-4 ml-2 hidden login__loading" icon="oval" color="#fff"/>
                                    </x-base.button>
                                </div>
                            </div>
                        </form>
                        <div class="border-t border-slate-200 h-[1px] relative mt-7 mb-7">
                            <span class="font-medium leading-none italic absolute left-0 right-0 mx-auto w-[35px] top-[-6px] bg-white text-center">OR</span>
                        </div>
                        <x-base.button as="a" href="{{ route('login.with.email') }}" class="w-full" variant="outline-success">
                            <x-base.lucide class="w-4 h-4 mr-2" icon="mail" />
                            <span class="signin-text">Continue with Email</span>
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
            
            // if($('#success-notification-toggle').length>0) {

            //     $("#success-notification-toggle").on("click", function () {
            //         // Init toastify
            //         Toastify({
            //         node: $("#success-notification-content")
            //         .clone()
            //         .removeClass("hidden")[0],
            //         duration: -1,
            //         newWindow: true,
            //         close: true,
            //         gravity: "top",
            //         position: "right",
            //         stopOnFocus: true,
            //         }).showToast();
            //         });

            //     $("#success-notification-toggle").trigger('click')            
            // }

            
            // async function login() {
            //     // Reset state
            //     $('#login-form').find('.login__input').removeClass('border-danger')
            //     $('#login-form').find('.login__input-error').html('')

            //     // Post form
            //     let email = $('#email').val()
            //     let password = $('#password').val()
            //     $('.signin-text').addClass('hidden');
            //     $('#btn-login .login__loading').removeClass('hidden');
            //     // Loading state
            //     //$('#btn-login').html('<i data-loading-icon="oval" data-color="white" class="w-5 h-5 mx-auto"></i>')
            //     //tailwind.svgLoader()
            //     await helper.delay(1500)

            //     axios.post(route('login.check'), {
            //         email: email,
            //         password: password
            //     }).then(res => {
            //         location.href = route('company.dashboard');
            //     }).catch(err => {
            //         //$('#btn-login').html('Login')
            //         $('#btn-login .login__loading').addClass('hidden');
            //         $('.signin-text').removeClass('hidden');
            //         if (err.response.data.message != 'Wrong email or password.') {
            //             for (const [key, val] of Object.entries(err.response.data.errors)) {
            //                 $(`#${key}`).addClass('border-danger')
            //                 $(`#error-${key}`).html(val)
            //             }
            //         } else {
            //             $(`#password`).addClass('border-danger')
            //             $(`#error-password`).html(err.response.data.message)
            //         }
            //     })
            // }

            // $('#login-form').on('keyup', function(e) {
            //     if (e.keyCode === 13) {
            //         login()
            //     }
            // })

            // $('#btn-login').on('click', function() {
            //     login()
            // })
        })()
    </script>
@endPushOnce
