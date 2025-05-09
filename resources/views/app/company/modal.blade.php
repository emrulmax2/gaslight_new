<!-- BEGIN: Add New User Modal Content -->
<x-base.dialog id="companyInformationModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="companyInformationForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center">Update Company Information</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div>
                    <x-base.form-label for="company_name">Company Name <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input class="cap-fullname" type="text" id="company_name" name="company_name" value="{{ isset($company->company_name) ? $company->company_name : '' }}" />
                    <div class="acc__input-error error-company_name text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="business_type">Company Business Type <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.tom-select id="business_type" name="business_type" class="w-full">
                        <option value="Company" {{ isset($company->business_type) && $company->business_type == 'Company' ? 'selected' : '' }}>Company</option>
                        <option value="Sole trader" {{ isset($company->business_type) && $company->business_type == 'Sole trader' ? 'selected' : '' }}>Sole Trader</option>
                        <option value="Other" {{ isset($company->business_type) && $company->business_type == 'Other' ? 'selected' : '' }}>Other</option>
                    </x-base.tom-select>
                    <div class="acc__input-error error-business_type text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3 registrationWrap" style="display: {{ isset($company->business_type) && $company->business_type == 'Company' ? 'block' : 'none' }};">
                    <x-base.form-label for="company_registration">Company Registration Number <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input type="text" id="company_registration" name="company_registration"  value="{{ isset($company->company_registration) ? $company->company_registration : '' }}"/>
                    <div class="acc__input-error error-company_registration text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3 flex">
                    <label for="vat_number_check" class="cursor-pointer mr-5">VAT Registered</label>
                    <x-base.form-switch.input id="vat_number_check" name="vat_number_check" value="1" type="checkbox" checked="{{ isset($company->vat_number) ? 'checked' : '' }}" />
                </div>
                <div class="mt-3 vat_number_input {{ isset($company->vat_number) ? '' : 'hidden' }}">
                    <x-base.form-label for="vat_number">VAT Number <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input id="vat_number" type="text" name="vat_number" value="{{ isset($company->vat_number) ? $company->vat_number : '' }}" placeholder="VAT Number"/>
                    <div class="acc__input-error error-vat_number text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <div data-tw-merge class="flex items-center">
                        <label data-tw-merge for="display_company_name" class="cursor-pointer ml-0 mr-5">Display company name on certificates?</label>
                        <input data-tw-merge {{ (isset($company->display_company_name) && $company->display_company_name == 1 ? 'Checked' : '') }} type="checkbox" name="display_company_name" value="1" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type=&#039;radio&#039;]]:checked:bg-primary [&amp;[type=&#039;radio&#039;]]:checked:border-primary [&amp;[type=&#039;radio&#039;]]:checked:border-opacity-10 [&amp;[type=&#039;checkbox&#039;]]:checked:bg-primary [&amp;[type=&#039;checkbox&#039;]]:checked:border-primary [&amp;[type=&#039;checkbox&#039;]]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white" id="display_company_name" />
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="piUpdateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="company_id" value="{{ $company->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Company Information Modal Content -->
 
<!-- BEGIN: Company Registration Modal Content -->
<x-base.dialog id="companyRegistrationModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="companyRegistrationForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center">Update Registration Details</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div>
                    <x-base.form-label for="gas_safe_registration_no">Gas Safe Registration No</x-base.form-label>
                    <x-base.form-input type="text" name="gas_safe_registration_no"  value="{{ isset($company->gas_safe_registration_no) ? $company->gas_safe_registration_no : '' }}"/>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="registration_no">Registration No</x-base.form-label>
                    <x-base.form-input type="text" name="registration_no" value="{{ isset($company->registration_no) ? $company->registration_no : '' }}"/>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="register_body_id">Registration Body For</x-base.form-label>
                    <x-base.tom-select  name="register_body_id" class="w-full" >
                        <option value="">Please Select</option>
                        @if($registerBodies->isNotEmpty())
                            @foreach($registerBodies as $bdy)
                                <option value="{{ $bdy->id }}" {{ $company->registration_body_for == $bdy->id ? 'selected' : ''}}>{{ $bdy->name }}</option>
                            @endforeach
                        @endif
                    </x-base.tom-select>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="registration_body_for_legionella">Registration Body For Legionella Risk Assessment</x-base.form-label>
                    <x-base.form-input type="text" name="registration_body_for_legionella" value="{{ isset($company->registration_body_for_legionella) ? $company->registration_body_for_legionella : '' }}"/>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="registration_body_no_for_legionella">Registration No For Legionella Risk Assessment</x-base.form-label>
                    <x-base.form-input  type="text" name="registration_body_no_for_legionella" value="{{ isset($company->registration_body_no_for_legionella) ? $company->registration_body_no_for_legionella : '' }}"/>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="rdUpdateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="company_id" value="{{ $company->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Company Registration Modal Content -->
 
<!-- BEGIN: Company Contact Details Modal Content -->
<x-base.dialog id="companyContactModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="companyContactForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center">Update Contact Details</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div>
                    <x-base.form-label for="company_phone">Phone Number</x-base.form-label>
                    <x-base.form-input type="text" name="company_phone" value="{{ isset($company->company_phone) ? $company->company_phone : '' }}"/>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="company_web_site">Company Website</x-base.form-label>
                    <x-base.form-input type="url" name="company_web_site" value="{{ isset($company->company_web_site) ? $company->company_web_site : '' }}"/>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="company_tagline">Company Tagline</x-base.form-label>
                    <x-base.form-input type="text" name="company_tagline" value="{{ isset($company->company_tagline) ? $company->company_tagline : '' }}" />
                </div>
                <div class="mt-3">
                    <x-base.form-label for="company_email">Admin Email</x-base.form-label>
                    <x-base.form-input type="text" name="company_email" value="{{ isset($company->company_email) ? $company->company_email : '' }}"/>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="cdUpdateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="company_id" value="{{ $company->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Company Contact Details Modal Content -->
 
<!-- BEGIN: Company Address Modal Content -->
<x-base.dialog id="companyAddressModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="companyAddressForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center">Update Address</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="theAddressWrap" id="companyAddressWrap">
                <div>
                    <x-base.form-label for="customer_address_line_1">Address Lookup</x-base.form-label>
                    <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                </div>
                <div class="mt-3">
                    <x-base.form-label for="company_address_line_1">Address Line 1 <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($company->company_address_line_1) ? $company->company_address_line_1 : '' }}" name="company_address_line_1" id="company_address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
                    <div class="acc__input-error error-company_address_line_1 text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="company_address_line_2">Address Line 2 <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($company->company_address_line_2) ? $company->company_address_line_2 : '' }}" name="company_address_line_2" id="company_address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2" />
                    <div class="acc__input-error error-company_address_line_2 text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="company_city">Town/City <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($company->company_city) ? $company->company_city : '' }}" name="company_city" id="company_city" class="w-full city" type="text" placeholder="Town/City" />
                    <div class="acc__input-error error-company_city text-danger text-xs mt-1"></div>
                </div>
                <div  class="mt-3">
                    <x-base.form-label for="company_state">Region/County</x-base.form-label>
                    <x-base.form-input value="{{ isset($company->company_state) ? $company->company_state : '' }}" name="company_state" id="company_state" class="w-full state" type="text" placeholder="Region/County" />
                </div>
                <div class="mt-3">
                    <x-base.form-label for="company_postal_code">Post Code <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($company->company_postal_code) ? $company->company_postal_code : '' }}" name="company_postal_code" id="company_postal_code" class="w-full postal_code" type="text" placeholder="Post Code" />
                    <div class="acc__input-error error-company_postal_code text-danger text-xs mt-1"></div>
                </div>
                <x-base.form-input value="{{ isset($company->company_country) ? $company->company_country : '' }}" name="company_country" id="company_country" class="w-full country" type="hidden" />
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="adrUpdateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="company_id" value="{{ $company->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Company Address Modal Content -->
 
<!-- BEGIN: Company Bank Details Modal Content -->
<x-base.dialog id="companyBankModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="companyBankForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center">Update Bank Details</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div>
                    <x-base.form-label for="bank_name">Bank Name</x-base.form-label>
                    <x-base.form-input type="text" name="bank_name" value="{{ optional($company->companyBankDetails)->bank_name ?? '' }}"/>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="name_on_account">Account Name</x-base.form-label>
                    <x-base.form-input type="text" name="name_on_account" value="{{ optional($company->companyBankDetails)->name_on_account ?? '' }}"/>
                </div>
                <div class="mt-3">     
                    <x-base.form-label for="sort_code">Sort Code</x-base.form-label>
                    <x-base.form-input type="number" name="sort_code" value="{{ optional($company->companyBankDetails)->sort_code ?? '' }}"/>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="account_number">Account Number</x-base.form-label>
                    <x-base.form-input  type="number" name="account_number" value="{{ optional($company->companyBankDetails)->account_number ?? '' }}"/>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="payment_term">Payment Terms</x-base.form-label>
                    <x-base.form-textarea.index name="payment_term" rows="3"> {{ optional($company->companyBankDetails)->payment_term ?? '' }} </x-base.form-textarea.index>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="bdUpdateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="company_id" value="{{ $company->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Company Bank Details Modal Content -->