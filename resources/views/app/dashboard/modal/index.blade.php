<x-base.dialog id="inviteaFriendModal">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="inviteaFriendForm">
            <x-base.dialog.description class="p-5">
                <h2 class="text-center text-2xl text-primary font-bold tracking-normal mb-2">Invite a engineer and get free subscription</h2>
                <p class="text-center mb-5">You both will get a promo when your invited engineer make his first subscription. <a href="#" class="text-primary font-medium underline">See More</a></p>
                <div class="flex justify-start items-start mb-5">
                    <span class="bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">
                        <x-base.lucide class="h-5 w-5 text-success" icon="calendar-plus" />
                    </span>
                    <div>
                        <h3 class="text-lg text-primary font-bold tracking-[1] mb-1">You will get {{ env('REFERRER_TRAIL', 30) }} days free subscription</h3>
                        <p>
                            Once your friend will subscribe for first time you will additionally {{ env('REFERRER_TRAIL', 30) }} days free subscription.
                        </p>
                    </div>
                </div>
                <div class="flex justify-start items-start">
                    <span class="bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">
                        <x-base.lucide class="h-5 w-5 text-success" icon="calendar-plus2" />
                    </span>
                    <div>
                        <h3 class="text-lg text-primary font-bold tracking-[1] mb-1">They will get {{ env('REFEREE_TRAIL', 90) }} days free subscription</h3>
                        <p>
                            Your friend will get a {{ env('REFEREE_TRAIL', 90) }} days free trail period. Normaly the free trails only available for {{ env('DEFAULT_TRAIL', 14) }} days.
                        </p>
                    </div>
                </div>

                <div class="bg-slate-100 flex items-center justify-between rounded-sm mt-6 px-5 py-3">
                    <span>{{ (isset($user->referral->code) && !empty($user->referral->code) ? $user->referral->code : 'N/A') }}</span>
                    <x-base.button type="button" data-code="{{ (isset($user->referral->code) && !empty($user->referral->code) ? $user->referral->code : 'N/A') }}" size="sm" class="px-4 ml-auto rounded-full text-white" variant="success" id="copyCodeBtn">
                        Copy
                    </x-base.button>
                </div>

                <div class="shareButtons flex items-center justify-center mt-5">
                    <x-base.button target="_blank" as="a" href="https://www.facebook.com/sharer/sharer.php?u=http://app.gascertificate.app/" class="w-auto rounded-full text-white mr-1 px-3" size="sm" variant="facebook" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="Facebook" />
                        Facebook
                    </x-base.button>
                    <x-base.button as="a" href="mailto:friend@xyz.com?subject=Gas Safe Engineer APP Invitation&body=Dear, here is the referral code for Gas Safe Engineer registration : {{ (isset($user->referral->code) && !empty($user->referral->code) ? $user->referral->code : 'N/A') }}. Thanks and Regards" class="w-auto rounded-full text-white mr-1 px-3" size="sm" variant="linkedin" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                        Email
                    </x-base.button>
                    <x-base.button data-tw-toggle="modal" data-tw-target="#sendSmsInvitationModal" type="button" data-code="{{ (isset($user->referral->code) && !empty($user->referral->code) ? $user->referral->code : 'N/A') }}" class="sendSMSBtn w-auto rounded-full text-white px-3" size="sm" variant="instagram" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="smartphone" />
                        SMS
                    </x-base.button>
                </div>
            </x-base.dialog.description>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>

<!-- BEGIN: SEND SMS Invitation Modal Content -->
<x-base.dialog id="sendSmsInvitationModal" staticBackdrop>
    <x-base.dialog.panel>
        <form method="post" action="#" id="sendSmsInvitationForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Send SMS Invitation</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description >
                <div class="mb-3">
                    <x-base.form-label for="phone_numbers">Mobile Numbers <span class="text-danger">*</span></x-base.form-label>
                    <x-base.form-input name="phone_numbers" id="phone_numbers" class="w-full" type="text" placeholder="+44 123 456 7890, +44 123 456 7890" />
                    <div class="text-xs text-slate-500 mt-2">Use comma (,) seperator for multiple numbers.</div>
                    <div class="acc__input-error error-phone_numbers text-danger text-xs mt-1"></div>
                </div>
                <div>
                    <x-base.form-label for="messages">Messages <span class="text-danger">*</span></x-base.form-label>
                    <x-base.form-textarea name="messages" id="messages" class="w-full" data-text="Dear, here is the referral code for Gas Safe Engineer registration : {{ (isset($user->referral->code) && !empty($user->referral->code) ? $user->referral->code : 'N/A') }}. Thanks and Regards" rows="5" placeholder="Your message...">Dear, here is the referral code for Gas Safe Engineer registration : {{ (isset($user->referral->code) && !empty($user->referral->code) ? $user->referral->code : 'N/A') }}. Thanks and Regards</x-base.form-textarea>
                    <div class="acc__input-error error-messages text-danger text-xs mt-1"></div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="sendMessageBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Send Message
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: SEND SMS Invitation Modal Content -->


<!-- BEGIN: Modal Content -->
<x-base.dialog id="dashboard-firstlogin-preview" size="xl" staticBackdrop >
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-8 w-8 text-success" icon="CheckCircle" />
            <div id="titleModal" class="mt-5 text-2xl">Let’s do the initial setup</div>
        </div>
        <div id="step-1" class=" flex flex-col px-5 lg:px-16 pb-16">
            <form id="step1-form" action="" method="POST">
                <input type="hidden" name="company_logo" id="company_logo" />
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
            <div>
                <div
                    class="mt-5 block flex-col first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                    <div class="hidden sm:block">
                        <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                            <div class="text-left">
                                <div class="flex items-center">
                                    <div class="font-medium">Organization/Company</div>
                                    <div class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400 ">
                                        Required
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                        <div class="flex flex-col items-center md:flex-row">
                            <x-base.form-input type="text" class="step1__input" placeholder="Organization/Company" name="company_name" />
                        </div>
                        <div id="error-company_name" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                    </div>
                </div>
                <div
                    class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                    <div class="hidden sm:block">
                        <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                            <div class="text-left">
                                <div class="flex items-center">
                                    <div class="font-medium">Phone Number</div>
                                    <div
                                        class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400">
                                        Required
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                        <div class="flex flex-col items-center md:flex-row">
                            <x-base.form-input
                            type="text"
                            placeholder="+44 123 456 7890"
                            name="company_phone"
                            />
                        </div>
                        
                        <div id="error-company_phone" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                    </div>
                </div>
                
                <div
                    class=" block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                  <div class="hidden sm:block">
                    <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                        <div class="text-left">
                            <div class="flex items-center">
                                <div class="font-medium">Business Type</div>
                                <div
                                    class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400">
                                    Required
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                    <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                        <div class="flex items-center flex-row gap-2 justify-between">
                            <div
                                class="w-full whitespace-nowrap rounded-md border border-slate-300/60 bg-white px-1 lg:px-3 py-2 shadow-sm first:rounded-b-none last:-mt-px last:rounded-t-none focus:z-10 first:md:rounded-r-none first:md:rounded-bl-md last:md:-ml-px last:md:mt-0 last:md:rounded-l-none last:md:rounded-tr-md [&:not(:first-child):not(:last-child)]:-mt-px [&:not(:first-child):not(:last-child)]:rounded-none [&:not(:first-child):not(:last-child)]:md:-ml-px [&:not(:first-child):not(:last-child)]:md:mt-0">
                                <x-base.form-check>
                                    <x-base.form-check.input
                                        id="checkbox-switch-4"
                                        type="radio"
                                        value="Sole trader"
                                        name="business_type"
                                    />
                                    <x-base.form-check.label for="checkbox-switch-4 text-sm">
                                        Sole trader
                                    </x-base.form-check.label>
                                </x-base.form-check>
                            </div>
                            
                            <div
                                class="w-full  border border-slate-300/60 bg-white px-1 lg:px-3 py-2 shadow-sm first:rounded-b-none last:-mt-px last:rounded-t-none focus:z-10 first:md:rounded-r-none first:md:rounded-bl-md last:md:-ml-px last:md:mt-0 last:md:rounded-l-none last:md:rounded-tr-md [&:not(:first-child):not(:last-child)]:-mt-px [&:not(:first-child):not(:last-child)]:rounded-none [&:not(:first-child):not(:last-child)]:md:-ml-px [&:not(:first-child):not(:last-child)]:md:mt-0">
                                <x-base.form-check>
                                    <x-base.form-check.input
                                        id="checkbox-switch-5"
                                        type="radio"
                                        value="Company"
                                        name="business_type"
                                    />
                                    <x-base.form-check.label for="checkbox-switch-5">
                                        Company
                                    </x-base.form-check.label>
                                </x-base.form-check>
                            </div>
                            <div
                                class="w-full rounded-md border border-slate-300/60 bg-white px-1 lg:px-3 py-2 shadow-sm first:rounded-b-none last:-mt-px last:rounded-t-none focus:z-10 first:md:rounded-r-none first:md:rounded-bl-md last:md:-ml-px last:md:mt-0 last:md:rounded-l-none last:md:rounded-tr-md [&:not(:first-child):not(:last-child)]:-mt-px [&:not(:first-child):not(:last-child)]:rounded-none [&:not(:first-child):not(:last-child)]:md:-ml-px [&:not(:first-child):not(:last-child)]:md:mt-0">
                                <x-base.form-check>
                                    <x-base.form-check.input
                                        id="checkbox-switch-6"
                                        type="radio"
                                        value="Other"
                                        name="business_type"
                                    />
                                    <x-base.form-check.label for="checkbox-switch-6">
                                        Other
                                    </x-base.form-check.label>
                                </x-base.form-check>
                            </div>
                        </div>
                        <div id="error-business_type" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                    </div>
                </div>
                <div id="company_register_no"
                    class="mt-0 lg:mt-5 flex-col pt-0 lg:pt-5 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0" style="display: none;">
                    <div class="hidden sm:block">
                        <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                            <div class="text-left">
                                <div class="flex items-center">
                                    <div class="font-medium">Registration Number</div>
                                    <div
                                        class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400 hidden sm:block">
                                        Required
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                        <x-base.form-input
                            type="text"
                            placeholder="Registration Number"
                            name="company_registration"
                        />
                        
                        <div id="error-company_registration" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                    </div>
                </div>

                <div class="pt-0 lg:pt-3 theAddressWrap" id="customerAddressWrap">
                    <div class="mt-5 block flex-col pt-5 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                        <div class="hidden sm:block">
                            <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                                <div class="text-left">
                                    <div class="flex items-center">
                                        <div class="font-medium">Address Lookup</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                            <x-base.form-input class="theAddressLookup" type="text" placeholder="Search address here..." id="user_fl_address_lookup" name="address_lookup" />
                        </div>
                    </div>
                
                    <div class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                       <div class="hidden sm:block">
                        <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                            <div class="text-left">
                                <div class="flex items-center">
                                    <div class="font-medium">Address Line 1</div>
                                    <div
                                        class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400 hidden sm:block">
                                        Required
                                    </div>
                                </div>
                            </div>
                        </div>
                       </div>
                        <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                            <x-base.form-input
                                type="text"
                                placeholder="123 Main Street"
                                name="company_address_line_1"
                                class="address_line_1"
                            />
                            
                            <div id="error-company_address_line_1" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                        </div>
                    </div>
                    <div
                        class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                        <div class="hidden sm:block">
                            <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                                <div class="text-left">
                                    <div class="flex items-center">
                                        <div class="font-medium">Address Line 2</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                            <x-base.form-input
                                type="text"
                                placeholder="Apartment 123"
                                name="company_address_line_2"
                                class="address_line_2"
                            />
                        </div>
                    </div>
                    <div
                        class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                        <div class="hidden sm:block">
                            <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                                <div class="text-left">
                                    <div class="flex items-center">
                                        <div class="font-medium">Town</div>
                                        <div
                                            class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400 hidden sm:block">
                                            Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                            <x-base.form-input
                                type="text"
                                placeholder="London"
                                name="company_city"
                                class="city"
                            />
                            <div id="error-company_city" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                        </div>
                    </div>
                    <div
                        class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                        <div class="hidden sm:block">
                            <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                                <div class="text-left">
                                    <div class="flex items-center">
                                        <div class="font-medium">County</div>
                                        <div
                                            class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400 hidden sm:block">
                                            Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                            <x-base.form-input
                                type="text"
                                placeholder="London"
                                name="company_state"
                                class="state"
                            />
                            <div id="error-company_state" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                        </div>
                    </div>
                    <div
                        class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                        <div class="hidden sm:block">
                            <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                                <div class="text-left">
                                    <div class="flex items-center">
                                        <div class="font-medium">Country</div>
                                        <div
                                            class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400 hidden sm:block">
                                            Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                            <x-base.form-input
                                type="text"
                                placeholder="London"
                                name="company_country"
                                class="country"
                            />
                            <div id="error-company_country" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                        </div>
                    </div>
                    <div
                        class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                        <div class="hidden sm:block">
                            <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                                <div class="text-left">
                                    <div class="flex items-center">
                                        <div class="font-medium">Post Code</div>
                                        <div
                                            class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400 hidden sm:block">
                                            Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                            <x-base.form-input
                                type="text"
                                placeholder="SW1W 0NY"
                                name="company_postal_code"
                                class="postal_code"
                            />
                            <div id="error-company_postal_code" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                        </div>
                    </div>
                    <x-base.form-input name="latitude" id="latitude" class="w-full latitude" type="hidden" value="" />
                    <x-base.form-input name="longitude" id="longitude" class="w-full longitude" type="hidden" value="" />
                </div>

            <div class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                <div class="hidden sm:block">
                    <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                        <div class="text-left">
                            <div class="flex items-center">
                                <div class="font-medium">Gas Safe Registration Number</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                    <x-base.form-input
                        type="text"
                        placeholder="Gas Safe Registration Number"
                        name="gas_safe_registration_no"
                        class="gas_safe_registration_no"
                    />
                    <div id="error-gas_safe_registration_no" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                </div>
            </div>
            <div class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                <div class="hidden sm:block">
                    <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                        <div class="text-left">
                            <div class="flex items-center">
                                <div class="font-medium">Gas Safe ID Card Number</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                    <x-base.form-input
                        type="text"
                        placeholder="Gas Safe ID Card Number"
                        name="gas_safe_id_card"
                        class="gas_safe_id_card"
                    />
                    <div id="error-gas_safe_id_card" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                </div>
            </div>

            <div class="pt-0 lg:pt-3 flex">
                <div class="flex items-center w-atuo xl:w-60 mr-2 xl:mr-14">
                    <label for="vat_number" class="cursor-pointer font-medium">VAT Registered</label>
                </div>
                <div class="flex-1">
                    <x-base.form-switch.input class="" id="vat_number" name="vat_number" value="1" type="checkbox" />
                </div>
            </div>
            <div class="vat_number_input hidden mt-3">
                <div class="block flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0 ">
                    <div class="hidden sm:block">
                        <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60">
                            <div class="text-left">
                                <div class="flex items-center">
                                    <div class="font-medium">Vat Number</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                        <x-base.form-input type="text" placeholder="GB123456789" name="company_vat"/>
                        <div id="error-company_vat" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                    </div>
                </div>
            </div>
            </div>
            </form>
            <div
                class="flex-col pt-0 lg:pt-3 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center mb-3 lg:mb-0">
                <div class="mb-2 sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-60 hidden">
                    <div class="text-left">
                        <div class="flex items-center">
                            <div class="font-medium">Company Logo</div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 w-full flex-1 xl:mt-0 hidden">
                        <form id="myDropzone" action="" class="dropzone [&.dropzone]:border-2 [&.dropzone]:border-dashed dropzone [&.dropzone]:border-slate-300/70 [&.dropzone]:bg-slate-50 [&.dropzone]:cursor-pointer [&.dropzone]:dark:bg-darkmode-600 [&.dropzone]:dark:border-white/5 dz-clickable" id="my-dropzone">
                            @csrf
                            <div class="fallback">
                                <input
                                    name="file"
                                    type="file"
                                />
                            </div>
                            <div class="dz-message">
                                <div class="text-lg font-medium">
                                    Drop files here or click to upload.
                                </div>
                                <div class="text-gray-600">
                                    This is just a demo dropzone. Selected files are
                                    <span class="font-medium">not</span> actually
                                    uploaded.
                                </div>
                            </div>
                            <input type="hidden" name="pid" value="{{ auth()->user()->id }}" />
                        </form>
                        <div id="uploaded-view" class="border-dashed pt-5 mt-5 border-slate-300/60 rounded border-2 px-3 hidden"></div>

                </div>
            </div>
            <div class="mt-0 lg:mt-6 flex border-t border-dashed border-slate-300/70 pt-5 md:justify-end ">
                <x-base.button
                    class="w-full border-primary/50 px-4 md:w-auto"
                    variant="outline-primary"
                    id="btn-step1"
                >
                    <span class="step1-text">Save and Exit</span>  <x-base.loading-icon
                                                        class="h-6 w-6 hidden step1__loading"
                                                        color="#475569"
                                                        icon="oval"
                                                    />
                </x-base.button>
            </div>
        </div>

    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->


<x-base.dialog id="successModal">
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-success" icon="CheckCircle" />
            <div class="mt-5 text-3xl successModalTitle">Success!</div>
            <div class="mt-2 text-slate-500 successModalDesc">Initial Setup Done</div>
        </div>
        <div class="px-5 pb-8 text-center">
            <x-base.button class="w-24 successCloser" data-action="NONE"  data-red="#" type="button" variant="primary">Ok</x-base.button>
        </div>
    </x-base.dialog.panel>
</x-base.dialog>


<!-- BEGIN: Warning Modal Content -->
<x-base.dialog id="warningModal" class="max-w-full">
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="AlertOctagon" />
            <div class="mt-5 text-3xl warningModalTitle">Good job!</div>
            <div class="mt-2 text-slate-500 warningModalDesc">You clicked the button! </div>
        </div>
        <div class="px-5 pb-8 text-center">
            <x-base.button class="w-24" data-tw-dismiss="modal" type="button" variant="primary">Ok</x-base.button>
        </div>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Warning Modal Content -->

<!-- BEGIN: Modal Content -->
{{--<x-base.dialog id="createCertificateOrInvModal">
    <x-base.dialog.panel>
        <form method="post" action="#" id="createCertificateOrInvForm">
            <x-base.dialog.description class="p-0">
                @if($forms->isNotEmpty())
                    @foreach($forms as $form)
                        <div class="box mb-0 shadow-none rounded-none border-none">
                            <div class="flex flex-col items-center bg-slate-200/60 px-3 py-3 dark:border-darkmode-400 sm:flex-row">
                                <h2 class="mr-auto font-medium text-dark">
                                    {{ $form->name }}
                                </h2>
                            </div>
                            <div>
                                @if(isset($form->childs) && $form->childs->count() > 0)
                                    @foreach($form->childs as $child)
                                        <a href="{{ route('jobs', ['record' => $child->slug]) }}" class="flex w-full border-b border-b-slate-200/60 bg-white px-3 py-2 hover:text-primary justify-start items-center">
                                            <x-base.lucide class="mr-3 h-3 w-3" icon="check-circle" />
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </x-base.dialog.description>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>--}}
<!-- END: Modal Content -->