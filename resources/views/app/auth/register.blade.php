@extends('../themes/base')

@section('head')
    <title>Gas Certificate - New Registration </title>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async defer></script>
@endsection

@section('content')
    <div class="container grid grid-cols-12 px-5 py-10 sm:px-10 sm:py-14 md:px-36 lg:h-screen lg:max-w-[1550px] lg:py-0 lg:pl-14 lg:pr-12 xl:px-24 2xl:max-w-[1750px]">
        <div @class([ 'relative z-50 h-full col-span-12 p-7 sm:p-14 bg-white rounded-2xl lg:bg-transparent lg:pr-10 lg:col-span-5 xl:pr-24 2xl:col-span-4 lg:p-0', "before:content-[''] before:absolute before:inset-0 before:-mb-3.5 before:bg-white/40 before:rounded-2xl before:mx-5", ])>
            <div class="relative z-10 flex h-full w-full flex-col justify-center py-2 lg:py-24">
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
                    <div class="text-2xl font-medium">Sign Up with Mobile</div>
                    <div class="mt-2.5 text-slate-600">
                        Already have an account?
                        <a
                            class="font-medium text-primary"
                            href="{{ route('login') }}"
                        >
                            Sign In
                        </a>
                    </div>
                </div>
                <form class="form-wizard" id="userRegistrationForm" action="#" enctype="multipart/form-data">
                    <div class="wizard-fieldset mt-10 show" id="stepMobileNumber">
                        <div class="text-base font-medium">Mobile Number</div>
                        <div class="mt-5 grid grid-cols-12 gap-4 gap-y-5">
                            <div class="intro-y col-span-12">
                                <x-base.input-group inputGroup>
                                    <x-base.input-group.text id="input-group-email" class="inline-flex items-center pr-5">
                                        <img src="{{ Vite::asset('resources/images/flags/uk.svg') }}" class="w-5 h-auto mr-2" alt="UK Flag"/>
                                        +44
                                    </x-base.input-group.text>
                                    <x-base.form-input type="number" id="mobileNumber" name="mobile" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="07123456789" />
                                </x-base.input-group>
                                <div class="acc__input-error error-mobile mt-2 text-danger text-xs" style="display: none;"></div>
                            </div>
                            <div class="intro-y col-span-12">
                                <!-- <div class="cf-turnstile" data-sitekey="0x4AAAAAACaCFao-oSn6uOqg"></div> -->
                                <div id="turnstile-container"></div>
                            </div>

                            <div class="intro-y col-span-12 mt-5 flex items-center justify-center sm:justify-between">
                                <x-base.button type="button" disabled class="ml-2 sm:ml-auto w-auto min-w-24 form-wizard-next-btn disabled:cursor-not-allowed" variant="primary" >
                                    Get OTP
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-fieldset mt-10" id="stepVerifiedOtp">
                        <div class="text-base font-medium">OTP Verification</div>
                        <div class="mt-5 grid grid-cols-12 gap-4 gap-y-5">
                            <div class="intro-y col-span-12">
                                Enter the code from the SMS we sent to <span class="mobileNumberShow font-bold text-dark"></span>
                            </div>
                            <div class="intro-y col-span-12">
                                <div id="countdown" class="font-medium text-center text-success leading-none mb-3">03:00</div>
                                <div class="flex justify-center items-center">
                                    <x-base.form-input id="otp1" name="otp_1" type="number" class="w-[35px] mx-1 text-center px-0 otpCodes font-bold" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1" />
                                    <x-base.form-input id="otp2" name="otp_2" type="number" class="w-[35px] mx-1 text-center px-0 otpCodes font-bold" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1" />
                                    <x-base.form-input id="otp3" name="otp_3" type="number" class="w-[35px] mx-1 text-center px-0 otpCodes font-bold" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1" />
                                    <x-base.form-input id="otp4" name="otp_4" type="number" class="w-[35px] mx-1 text-center px-0 otpCodes font-bold" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1" />
                                </div>
                                <div class="acc__input-error error-otp mt-2 text-danger text-center text-xs"></div>
                                <div id="countDownHtml" class="text-center leading-none mt-5" style="display: none;">
                                    Don't received the OTP? <a href="javascript:void(0);" class="font-medium text-primary underline" id="resendOtp">RESEND</a>
                                </div>
                            </div>

                            <div class="intro-y col-span-12 mt-5 flex items-center justify-center sm:justify-between">
                                <x-base.button type="button"
                                    class="w-24 form-wizard-previous-btn"
                                    variant="secondary"
                                >
                                    Previous
                                </x-base.button>
                                <x-base.button type="button"
                                    class="ml-2 sm:ml-auto w-auto min-w-24 form-wizard-next-btn disabled:cursor-not-allowed"
                                    variant="primary"
                                >
                                    Next
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-fieldset mt-10" id="stepBusinessInfo">
                        <div class="text-base font-medium">Business Information</div>
                        <div class="mt-5 grid grid-cols-12 gap-4 gap-y-5">
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="name">Name <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input id="name" name="name" class="require" type="text" />
                                <div class="acc__input-error error-name mt-2 text-danger text-left text-xs"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="company_name">Business Name <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input id="company_name" name="company_name" type="text" class="require" />
                                <div class="acc__input-error error-company_name mt-2 text-danger text-left text-xs"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="business_type">Business Type <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-select id="business_type" name="business_type" class="require">
                                    <option>Please Select</option>
                                    <option value="Company">Company</option>
                                    <option value="Sole trader">Sole Trader</option>
                                    <option value="Other">Other</option>
                                </x-base.form-select>
                                <div class="acc__input-error error-business_type mt-2 text-danger text-left text-xs"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6 registrationWrap" style="display: none;">
                                <x-base.form-label for="company_registration">Registration Number <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input id="company_registration" name="company_registration" type="text" />
                                <div class="acc__input-error error-company_registration mt-2 text-danger text-left text-xs"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6 flex items-center">
                                <div class="flex">
                                    <label for="vat_number_check" class="cursor-pointer pt-1 mr-5">VAT Registered</label>
                                    <x-base.form-switch.input id="vat_number_check" name="vat_number_check" value="1" type="checkbox" />
                                </div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6 vatNumberInput" style="display: none;">
                                <x-base.form-label for="vat_number">VAT Number <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input id="vat_number" type="text" name="vat_number" value=""/>
                                <div class="acc__input-error error-vat_number mt-2 text-danger text-left text-xs"></div>
                            </div>
                            <div class="intro-y col-span-12">
                                <x-base.form-label for="name">Email <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input id="email" name="email" type="email" class="require" />
                                <div class="acc__input-error error-email mt-2 text-danger text-left text-xs"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label>Password <span class="text-danger ml-2">*</span></x-base.form-label>
                                <div class="relative">
                                    <x-base.form-input type="password" placeholder="************" name="password" id="password" />
                                    <span id="togglePasswordShow" class="absolute inset-y-0 right-0 flex items-left pr-3 cursor-pointer">
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

                            <div class="intro-y col-span-12 mt-5 flex items-center justify-center sm:justify-between">
                                <x-base.button type="button"
                                    class="w-24 form-wizard-previous-btn"
                                    variant="secondary"
                                >
                                    Previous
                                </x-base.button>
                                <x-base.button type="button"
                                    class="ml-2 sm:ml-auto w-auto min-w-24 form-wizard-next-btn disabled:cursor-not-allowed"
                                    variant="primary"
                                >
                                    Next
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-fieldset mt-10" id="stepContactDetails">
                        <div class="text-base font-medium">Contact Details</div>
                        <div class="mt-5 grid grid-cols-12 gap-4 gap-y-5 theAddressWrap" id="companyAddressWrap">
                            <div class="intro-y col-span-12">
                                <x-base.form-label for="customer_address_line_1">Address Lookup</x-base.form-label>
                                <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="company_address_line_1">Address Line 1 <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input value="" name="company_address_line_1" id="company_address_line_1" class="w-full address_line_1 require" type="text" placeholder="Address Line 1" />
                                <div class="acc__input-error error-company_address_line_1 text-danger text-xs mt-1"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="company_address_line_2">Address Line 2 <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input value="" name="company_address_line_2" id="company_address_line_2" class="w-full address_line_2 require" type="text" placeholder="Address Line 2" />
                                <div class="acc__input-error error-company_address_line_2 text-danger text-xs mt-1"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="company_city">Town/City <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input value="" name="company_city" id="company_city" class="w-full city require" type="text" placeholder="Town/City" />
                                <div class="acc__input-error error-company_city text-danger text-xs mt-1"></div>
                            </div>
                            <div  class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="company_state">Region/County</x-base.form-label>
                                <x-base.form-input value="" name="company_state" id="company_state" class="w-full state" type="text" placeholder="Region/County" />
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="company_postal_code">Post Code <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input value="" name="company_postal_code" id="company_postal_code" class="w-full postal_code require" type="text" placeholder="Post Code" />
                                <div class="acc__input-error error-company_postal_code text-danger text-xs mt-1"></div>
                            </div>
                            <x-base.form-input value="" name="company_country" id="company_country" class="w-full country" type="hidden" />
                            
                            <div class="intro-y col-span-12 mt-5 flex items-center justify-center sm:justify-between">
                                <x-base.button type="button"
                                    class="w-24 form-wizard-previous-btn"
                                    variant="secondary"
                                >
                                    Previous
                                </x-base.button>
                                <x-base.button type="button"
                                    class="ml-2 sm:ml-auto w-auto min-w-24 form-wizard-next-btn disabled:cursor-not-allowed"
                                    variant="primary"
                                >
                                    Next
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-fieldset mt-10" id="stepOtherInfo">
                        <div class="text-base font-medium">Other Information</div>
                        <div class="mt-5 grid grid-cols-12 gap-4 gap-y-5 theAddressWrap" id="companyAddressWrap">
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="gas_safe_registration_no">Gas Safe Reg. No <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input id="gas_safe_registration_no" name="gas_safe_registration_no" class="require" type="text" />
                                <div class="acc__input-error error-gas_safe_registration_no text-danger text-xs mt-1"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label for="gas_safe_id_card">Gas Safe ID Card No <span class="text-danger ml-2">*</span></x-base.form-label>
                                <x-base.form-input id="gas_safe_id_card" name="gas_safe_id_card" class="require" type="text" />
                                <div class="acc__input-error error-gas_safe_id_card text-danger text-xs mt-1"></div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <x-base.form-label>Referral Code</x-base.form-label>
                                <x-base.form-input class="block" type="text" placeholder="" name="referral_code" id="referral_code" />
                                <div class="acc__input-error error-referral_code text-danger text-xs mt-1"></div>
                            </div>
                            
                            <div class="intro-y col-span-12 mt-5 flex items-center justify-center sm:justify-between">
                                <x-base.button type="button"
                                    class="w-24 form-wizard-previous-btn"
                                    variant="secondary"
                                >
                                    Previous
                                </x-base.button>
                                <x-base.button type="button"
                                    class="ml-2 sm:ml-auto w-auto min-w-24 form-wizard-next-btn disabled:cursor-not-allowed"
                                    variant="primary"
                                >
                                    Next
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-fieldset wizard-last-step mt-10" id="stepSignature">
                        <div class="text-base font-medium">Signature</div>
                        <div class="mt-5 grid grid-cols-12 gap-4 gap-y-5 theAddressWrap" id="companyAddressWrap">
                            <div class="intro-y col-span-12">
                                <div class="gsfSignature border rounded-[3px] h-auto py-0 bg-slate-100 rounded-b-none flex justify-center items-center">
                                    <x-creagia-signature-pad name='sign'
                                        border-color="#e5e7eb"
                                        submit-name="Save"
                                        clear-name="Clear Signature"
                                        submit-id="signSaveBtn"
                                        clear-id="clear"
                                        pad-classes="w-auto h-48 bg-white mt-0"
                                    />
                                    <div class="customeUploads my-10 border-2 border-dashed border-slate-500 flex items-center text-center h-[200px] max-h-[200px] sm:w-[70%] rounded-[5px] p-[20px]" style="display: none">
                                        <label for="signature_file" class="text-center upload-message my-[3em] relative w-full cursor-pointer">
                                            <div class="customeUploadsContent">
                                                <span class="text-lg font-medium">
                                                    Drop files here or click to upload.
                                                </span><br/>
                                                <span class="text-gray-600">
                                                    This is signature file upload. Selected files should<br/>
                                                    not over <span class="font-medium">2MB</span> and should be image file.
                                                </span><br/>
                                            </div>
                                            <img src="" alt="signature" id="signature_image" class="h-[80px] w-auto inline-block" style="display: none"/>
                                        </label>
                                        <input type="file" id="signature_file" name="signature_file" accept="image/*" class="w-0 h-0 opacity-0 absolute left-0 top-0"/>
                                    </div>
                                </div>
                                <div class="intSetupSignatureBtns flex">
                                    <x-base.button type="button" class="signBtns w-[50%] rounded-br-none active flex justify-center items-center rounded-t-none [&.active]:bg-success [&.active]:text-white" variant="secondary">
                                        Draw Signature
                                    </x-base.button>
                                    <x-base.button type="button" class="uploadBtns w-[50%] rounded-bl-none flex justify-center items-center rounded-t-none [&.active]:bg-success [&.active]:text-white" variant="secondary">
                                        Upload Signature
                                    </x-base.button>
                                </div>
                                <div class="acc__input-error error-signature text-danger text-xs mt-1"></div>
                            </div>
                            <div class="intro-y col-span-12" id="registerSuccess" style="display: none;">
                                <x-base.alert class="my-7 flex items-center rounded-[0.6rem] border-primary/20 bg-primary/5 px-4 py-3 leading-[1.7]" ariant="outline-success" >
                                    <div class="">
                                        <x-base.lucide class="mr-2 h-7 w-7 fill-primary/10 stroke-[0.8]" icon="Lightbulb" />
                                    </div>
                                    <div class="ml-1 mr-8">
                                        Register <span class="font-medium">Sucessful</span>! check the
                                        <span id="email" class="font-medium">given email</span> for the verification link.
                                    </div>
                                    <x-base.alert.dismiss-button class="btn-close text-primary">
                                        <x-base.lucide class="w-5 h-5" icon="X" />
                                    </x-base.alert.dismiss-button>
                                </x-base.alert>
                            </div>
                            <div class="intro-y col-span-12 mt-5 flex items-center justify-center sm:justify-between">
                                <x-base.button type="button"
                                    class="w-24 form-wizard-previous-btn"
                                    variant="secondary"
                                >
                                    Previous
                                </x-base.button>
                                <x-base.button type="button"
                                    id="submitTheFormWithSignature"
                                    class="ml-2 sm:ml-auto w-auto min-w-24 text-white"
                                    variant="success"
                                >
                                    Submit
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{--<div
        class="container grid grid-cols-12 px-5 py-10 sm:px-10 sm:py-14 md:px-36 lg:h-screen lg:max-w-[1550px] lg:py-0 lg:pl-14 lg:pr-12 xl:px-24 2xl:max-w-[1750px]">
        <div @class([
            'relative z-50 h-full col-span-12 p-7 sm:p-14 bg-white rounded-2xl lg:bg-transparent lg:pr-10 lg:col-span-5 xl:pr-24 2xl:col-span-4 lg:p-0',
            "before:content-[''] before:absolute before:inset-0 before:-mb-3.5 before:bg-white/40 before:rounded-2xl before:mx-5",
        ])>
            <div class="relative z-10 flex h-full w-full flex-col justify-center py-2 lg:py-24">
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
                    <div class="text-2xl font-medium">Sign Up</div>
                    <div class="mt-2.5 text-slate-600">
                        Already have an account?
                        <a
                            class="font-medium text-primary"
                            href="{{ route('login') }}"
                        >
                            Sign In
                        </a>
                    </div>
                    
                    <div class="mt-6">
                        <form
                        id="register-form"
                        class="mt-6"
                        method="POST">
                        <x-base.form-label>Full Name*</x-base.form-label>
                        <x-base.form-input
                            class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5 cap-fullname"
                            type="text"
                            placeholder="David Peterson"
                            name="name" 
                        />
                        
                        <div id="error-name" class="register__input-error text-danger mt-2 dark:text-orange-400"></div>
                        <x-base.form-label class="mt-3">Email*</x-base.form-label>
                        <x-base.form-input
                            class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5"
                            type="text"
                            placeholder="username@example.com"
                            name="email"
                        />
                        <div id="error-email" class="register__input-error text-danger mt-2 dark:text-orange-400"></div>

                        {{--<x-base.form-label class="mt-3">Role*</x-base.form-label>
                        <x-base.tom-select
                            class="block rounded-[1rem] border-slate-300/80 px-2 py-1.5"
                            data-placeholder="Please Select your Role"
                            name="role"
                        >
                            <option value="">Please Select</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </x-base.tom-select>
                        <div id="error-role" class="register__input-error text-danger mt-2 dark:text-orange-400"></div>--}}

                        <x-base.form-label class="mt-3">Password*</x-base.form-label>
                        <div class="relative">
                        <x-base.form-input
                            class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5"
                            type="password"
                            placeholder="************"
                            name="password"
                            id="password"
                        />
                        <span id="togglePasswordShow" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                            <i id="togglePasswordIcon" data-lucide="eye-off"></i>
                        </span>
                        </div>
                        
                        <div id="error-password" class="register__input-error text-danger mt-2 dark:text-orange-400"></div>
                        <div id="password-strength" class="mt-3.5 grid h-1.5 w-full grid-cols-12 gap-4">
                            <div id="strength-1" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            <div id="strength-2" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            <div id="strength-3" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                            <div id="strength-4" class="col-span-3 h-full rounded border border-slate-400/20 bg-slate-400/30"></div>
                        </div>
                        <a
                            class="mt-3 block text-xs text-slate-500/80 sm:text-sm"
                            href=""
                        >
                            What is a secure password?
                        </a>
                        <x-base.form-label class="mt-3">Password Confirmation*</x-base.form-label>
                        <div class="relative">
                            <x-base.form-input
                                class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5"
                                type="password"
                                placeholder="************"
                                name="password_confirmation"
                                id="password_confirmation"
                            />
                            <span id="toggleConfirmPasswordShow" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" >
                                <i id="togglePasswordConfirmationIcon" data-lucide="eye-off"></i>
                            </span>
                        </div>

                        <x-base.form-label class="mt-3">Referral Code</x-base.form-label>
                        <x-base.form-input
                            class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5"
                            type="text"
                            placeholder=""
                            name="referral_code"
                            id="referral_code"
                        />
                        <div id="error-referral_code" class="referral_code__input-error text-danger mt-2 dark:text-orange-400"></div>

                        <div class="mt-5 flex items-center text-xs text-slate-500 sm:text-sm">
                            <x-base.form-check.input
                                class="mr-2 border"
                                id="terms"
                                type="checkbox"
                                name="terms"
                            />
                            <label
                                class="cursor-pointer select-none"
                                for="terms"
                            >
                                I agree to the 
                            </label>
                            <a
                                class="ml-1 mr-1 text-primary dark:text-slate-200"
                                href=""
                            >Terms & Conditions</a> and
                            <a
                                class="ml-1 text-primary dark:text-slate-200"
                                href=""
                            >
                                Privacy Policy
                            </a>
                            .
                            
                        </div>
                        <div id="error-terms" class="register__input-error text-danger mt-2 dark:text-orange-400"></div>
                        {{-- <div class="g-recaptcha mt-5 text-center xl:mt-8 xl:text-left" data-sitekey="6Lcm-NkqAAAAAKHwPaoF1krwOMOtfVgB0V3N2nuD"></div> --}}
                        
                    </form>
                    <x-base.alert
                        id="register-success"
                        class="my-7 flex items-center rounded-[0.6rem] border-primary/20 bg-primary/5 px-4 py-3 leading-[1.7] hidden"
                        variant="outline-primary"
                    >
                        <div class="">
                            <x-base.lucide
                                class="mr-2 h-7 w-7 fill-primary/10 stroke-[0.8]"
                                icon="Lightbulb"
                            />
                        </div>
                        <div class="ml-1 mr-8">
                            Register <span class="font-medium">Sucessful</span>! check the
                            <span id="email" class="font-medium">given email</span> for the verification link.
                        </div>
                        <x-base.alert.dismiss-button class="btn-close text-primary">
                            <x-base.lucide
                                class="w-5 h-5"
                                icon="X"
                            />
                        </x-base.alert.dismiss-button>
                    </x-base.alert>
                        <div class="mt-5 text-center xl:mt-8 xl:text-left">
                            <x-base.button
                                id="btn-register"
                                class="w-full bg-gradient-to-r from-theme-1/70 to-theme-2/70 py-3.5 xl:mr-3"
                                variant="primary"
                                rounded
                            >
                                <span class="register-text">Register Now </span> <x-base.loading-icon
                                class="h-6 w-6 hidden register__loading"
                                icon="oval" color="#fff"
                            />
                            </x-base.button>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div> --}}
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
            <div class="sticky top-0 z-10 ml-16 hidden h-screen flex-col justify-center lg:flex xl:ml-28 2xl:ml-36">
                <div class="text-[2.6rem] font-medium leading-[1.4] text-white xl:text-3xl xl:leading-[1.2]">
                    Your Complete Solution for Fast, Reliable Gas Certificates
                </div>
                <div class="text-[2.6rem] font-medium leading-[1.4] text-white xl:text-3xl xl:leading-[1.2]">
                    Keeping Your Work Safe and Simple.
                </div>
                <div class="mt-5 text-base leading-relaxed text-white/70 xl:text-lg">
                    Empowering gas engineers with an effortless certification process, our app lets you issue gas certificates anytime, anywhere. Simplify your workflow and stay on top of your jobs with fast, reliable, and compliant certification at your fingertips.
                </div>
                <div class="mt-10 flex flex-col gap-3 xl:flex-row xl:items-center">
                    <div class="flex items-center">
                        {{--<div class="image-fit zoom-in h-9 w-9 2xl:h-11 2xl:w-11">
                            <x-base.tippy
                                class="rounded-full border-[3px] border-white/50"
                                src="{{ Vite::asset($users[0]['photo']) }}"
                                alt="Tailwise - Admin Dashboard Template"
                                as="img"
                                content="{{ $users[0]['name'] }}"
                            />
                        </div>
                        <div class="image-fit zoom-in -ml-3 h-9 w-9 2xl:h-11 2xl:w-11">
                            <x-base.tippy
                                class="rounded-full border-[3px] border-white/50"
                                src="{{ Vite::asset($users[1]['photo']) }}"
                                alt="Tailwise - Admin Dashboard Template"
                                as="img"
                                content="{{ $users[1]['name'] }}"
                            />
                        </div>
                        <div class="image-fit zoom-in -ml-3 h-9 w-9 2xl:h-11 2xl:w-11">
                            <x-base.tippy
                                class="rounded-full border-[3px] border-white/50"
                                src="{{ Vite::asset($users[2]['photo']) }}"
                                alt="Tailwise - Admin Dashboard Template"
                                as="img"
                                content="{{ $users[2]['name'] }}"
                            />
                        </div>
                        <div class="image-fit zoom-in -ml-3 h-9 w-9 2xl:h-11 2xl:w-11">
                            <x-base.tippy
                                class="rounded-full border-[3px] border-white/50"
                                src="{{ Vite::asset($users[0]['photo']) }}"
                                alt="Tailwise - Admin Dashboard Template"
                                as="img"
                                content="{{ $users[0]['name'] }}"
                            />
                        </div>--}}
                    </div>
                    <div class="text-base text-white/70 xl:ml-2 2xl:ml-3">
                        Over 7k+ strong and growing! Your journey begins here.
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <ThemeSwitcher /> --}}

@endsection

@pushOnce('styles')
    @vite('resources/css/custom/signature.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/utils/helper.js')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/toastify.js')
    @vite('resources/js/vendors/sign-pad.min.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/register.js')
    <script type="module">
        // Set Registration Token to Window Data.
        window.regToken = "{{ $regToken }}";
        window.turnstileSiteKey = "{{ config('services.turnstile.key') }}";

        document.addEventListener("DOMContentLoaded", function () {
            if (typeof turnstile !== "undefined" && document.getElementById("turnstile-container")) {
                window.turnstileWidget = turnstile.render("#turnstile-container", {
                    sitekey: window.turnstileSiteKey,
                    callback: function (token) {
                        window.turnstileToken = token;
                    }
                });
            } else {
                console.error("Turnstile not loaded or container missing");
            }
        });

        (function () {
            document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });

       
            // async function register() {
            //     // Reset state
            //     $('#register-form').find('.register__input').removeClass('border-danger')
            //     $('#register-form').find('.register__input-error').html('')

            //     // Create FormData object
            //     let formData = new FormData(document.getElementById('register-form'));

            //     $('.register-text').addClass('hidden');
            //     $('#btn-register .register__loading').removeClass('hidden');
            //     // Loading state
            //     await helper.delay(1500)

            //     axios.post(route('register'), formData)
            //         .then(res => {
            //             // Show Toastify message
            //             $("#register-success").removeClass('hidden');

            //             // Redirect to login after a short delay
            //             setTimeout(() => {
            //                 location.href = route('login');
            //             }, 3000);
            //         })
            //         .catch(err => {
            //             $('#btn-register .register__loading').addClass('hidden');
            //             $('.register-text').removeClass('hidden');
            //             if (err.response && err.response.data.errors) {
            //                 for (const [key, val] of Object.entries(err.response.data.errors)) {
            //                     $(`#${key}`).addClass('border-danger')
            //                     $(`#error-${key}`).html(val)
            //                 }
            //             } else if (err.response && err.response.data.error) {
            //                 Toastify({
            //                     text: err.response.data.error,
            //                     duration: 3000,
            //                     close: true,
            //                     gravity: "top", // `top` or `bottom`
            //                     position: "right", // `left`, `center` or `right`
            //                     backgroundColor: "#FF0000",
            //                 }).showToast();
            //             }
            //         });
            // }

            // $('#register-form').on('keyup', function(e) {
            //     if (e.keyCode === 13) {
            //         if($('#register-form #referral_code').val() != ''){
            //             $('.register-text').addClass('hidden');
            //             $('#btn-register .register__loading').removeClass('hidden');
            //             $.ajax({
            //                 type: 'POST',
            //                 data: {referral_code : $('#register-form #referral_code').val()},
            //                 url: route('register.validate.referral'),
            //                 headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            //                 async: false,
            //                 success: function(data) {
            //                     $('.register-text').removeClass('hidden');
            //                     $('#btn-register .register__loading').addClass('hidden');
            //                     if(data.suc == 1){
            //                         register();
            //                     }else{
            //                         $('#register-form').find('#referral_code').val('').addClass('border-danger')
            //                         $('#register-form').find('.referral_code__input-error').html('Invalid referral code.');

            //                         setTimeout(() => {
            //                             $('#register-form').find('#referral_code').removeClass('border-danger')
            //                             $('#register-form').find('.referral_code__input-error').html('');
            //                         }, 2000);
            //                     }
            //                 },
            //                 error:function(e){
            //                     console.log('Error');
            //                 }
            //             });
            //         }else{
            //             register();
            //         }
            //     }
            // })

            // $('#btn-register').on('click', function() {
            //     if($('#register-form #referral_code').val() != ''){
            //         $('.register-text').addClass('hidden');
            //         $('#btn-register .register__loading').removeClass('hidden');
            //         $.ajax({
            //             type: 'POST',
            //             data: {referral_code : $('#register-form #referral_code').val()},
            //             url: route('register.validate.referral'),
            //             headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            //             async: false,
            //             success: function(data) {
            //                 $('.register-text').removeClass('hidden');
            //                 $('#btn-register .register__loading').addClass('hidden');
            //                 if(data.suc == 1){
            //                     register();
            //                 }else{
            //                     $('#register-form').find('#referral_code').val('').addClass('border-danger')
            //                     $('#register-form').find('.referral_code__input-error').html('Invalid referral code.');

            //                     setTimeout(() => {
            //                         $('#register-form').find('#referral_code').removeClass('border-danger')
            //                         $('#register-form').find('.referral_code__input-error').html('');
            //                     }, 2000);
            //                 }
            //             },
            //             error:function(e){
            //                 console.log('Error');
            //             }
            //         });
            //     }else{
            //         register();
            //     }
            // })

            
        })();
    </script>
@endPushOnce