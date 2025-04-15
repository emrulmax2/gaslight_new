@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>User Settings</title>
@endsection

@section('subcontent')
<div class="intro-y mt-8 flex items-center">
    <h2 class="mr-auto text-lg font-medium">Company Setting</h2>
    <div class="flex">
        <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
            <x-base.lucide class="h-4 w-4" icon="home" />
        </x-base.button>
    </div>
</div>
<form method="post" action="#" id="companyStoreForm" enctype="multipart/form-data">
    <x-base.form-input type="hidden" name="company_id" id="company_id" value="{{ $company->id }}"/>
    <div class="grid grid-cols-12 gap-x-6 gap-y-10 mt-5">
        <div  class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-9 xl:col-span-9">
            <div  class="relative before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                <div class="intro-y box mb-5">
                    <!-- Header -->
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Company Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                <x-base.form-label for="company_name">Company Name</x-base.form-label>
                                <x-base.form-input class="cap-fullname" type="text" id="company_name" name="company_name" value="{{ isset($company->company_name) ? $company->company_name : '' }}" />
                            </div>
                            <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                <x-base.form-label for="business_type">Company Business Type</x-base.form-label>
                                <x-base.tom-select id="business_type" name="business_type" class="w-full">
                                    <option value="Company" {{ isset($company->business_type) && $company->business_type == 'Company' ? 'selected' : '' }}>Company</option>
                                    <option value="Sole trader" {{ isset($company->business_type) && $company->business_type == 'Sole trader' ? 'selected' : '' }}>Sole Trader</option>
                                    <option value="Other" {{ isset($company->business_type) && $company->business_type == 'Other' ? 'selected' : '' }}>Other</option>
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-6 md:col-span-4 registrationWrap" style="display: {{ isset($company->business_type) && $company->business_type == 'Company' ? 'block' : 'none' }};">
                                <x-base.form-label for="company_registration">Company Registration Number</x-base.form-label>
                                <x-base.form-input type="text" id="company_registration" name="company_registration"  value="{{ isset($company->company_registration) ? $company->company_registration : '' }}"/>
                            </div>
                            <div class="col-span-12 sm:col-span-6 md:col-span-4  pt-0 lg:pt-3 flex">
                                <div class="flex gap-3">
                                    <label for="vat_number_check" class="cursor-pointer">VAT Registered</label>
                                    <x-base.form-switch.input id="vat_number_check" name="vat_number_check" value="1" type="checkbox" checked="{{ isset($company->vat_number) ? 'checked' : '' }}" />
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-6 md:col-span-4  vat_number_input {{ isset($company->vat_number) ? '' : 'hidden' }}">
                                <x-base.form-label for="vat_number">VAT Number</x-base.form-label>
                                <x-base.form-input id="vat_number" type="text" name="vat_number" value="{{ isset($company->vat_number) ? $company->vat_number : '' }}" placeholder="VAT Number"/>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 md:col-span-4  mt-4">
                            <div data-tw-merge class="flex items-center">
                                <input data-tw-merge {{ (isset($company->display_company_name) && $company->display_company_name == 1 ? 'Checked' : '') }} type="checkbox" name="display_company_name" value="1" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type=&#039;radio&#039;]]:checked:bg-primary [&amp;[type=&#039;radio&#039;]]:checked:border-primary [&amp;[type=&#039;radio&#039;]]:checked:border-opacity-10 [&amp;[type=&#039;checkbox&#039;]]:checked:bg-primary [&amp;[type=&#039;checkbox&#039;]]:checked:border-primary [&amp;[type=&#039;checkbox&#039;]]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white" id="display_company_name" />
                                <label data-tw-merge for="display_company_name" class="cursor-pointer ml-2">Display company name on certificates?</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="intro-y box mb-5 z-20">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Registered Details</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                <x-base.form-label for="gas_safe_registration_no">Gas Safe Registration No</x-base.form-label>
                                <x-base.form-input type="text" name="gas_safe_registration_no"  value="{{ isset($company->gas_safe_registration_no) ? $company->gas_safe_registration_no : '' }}"/>
                            </div>
                            <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                <x-base.form-label for="registration_no">Registration No</x-base.form-label>
                                <x-base.form-input type="text" name="registration_no" value="{{ isset($company->registration_no) ? $company->registration_no : '' }}"/>
                            </div>
                            <div class="col-span-12 sm:col-span-6 md:col-span-4">
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
                            <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                <x-base.form-label for="registration_body_for_legionella">Registration Body For Legionella Risk Assessment</x-base.form-label>
                                <x-base.form-input type="text" name="registration_body_for_legionella" value="{{ isset($company->registration_body_for_legionella) ? $company->registration_body_for_legionella : '' }}"/>
                            </div>
                            <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                <x-base.form-label for="registration_body_no_for_legionella">Registration No For Legionella Risk Assessment</x-base.form-label>
                                <x-base.form-input  type="text" name="registration_body_no_for_legionella" value="{{ isset($company->registration_body_no_for_legionella) ? $company->registration_body_no_for_legionella : '' }}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-3 mb-5 z-10">
                    <div class="intro-y box">
                        <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                            <h2 class="mr-auto text-base font-medium">Contact Details</h2>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 gap-y-2">
                            <div>
                                <x-base.form-label for="company_phone">Phone Number</x-base.form-label>
                                <x-base.form-input type="text" name="company_phone" value="{{ isset($company->company_phone) ? $company->company_phone : '' }}"/>
                            </div>
                            <div >
                                <x-base.form-label for="company_web_site">Company Website</x-base.form-label>
                                <x-base.form-input type="text" name="company_web_site" value="{{ isset($company->company_web_site) ? $company->company_web_site : '' }}"/>
                            </div>
                            <div >
                                <x-base.form-label for="company_tagline">Company Tagline</x-base.form-label>
                                <x-base.form-input type="text" name="company_tagline" value="{{ isset($company->company_tagline) ? $company->company_tagline : '' }}" />
                            </div>
                            <div>
                                <x-base.form-label for="company_email">Admin Email</x-base.form-label>
                                <x-base.form-input type="text" name="company_email" value="{{ isset($company->company_email) ? $company->company_email : '' }}"/>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="intro-y box">
                        <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                            <h2 class="mr-auto text-base font-medium">Contact Details</h2>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 gap-y-2 theAddressWrap" id="companyAddressWrap">
                                <div>
                                    <x-base.form-label for="customer_address_line_1">Address Lookup</x-base.form-label>
                                    <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                                </div>
                                <div>
                                    <x-base.form-label for="company_address_line_1">Address Line 1</x-base.form-label>
                                    <x-base.form-input value="{{ isset($company->company_address_line_1) ? $company->company_address_line_1 : '' }}" name="company_address_line_1" id="company_address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
                                    <div class="acc__input-error error-company_address_line_1 text-danger text-xs mt-1"></div>
                                </div>
                                <div>
                                    <x-base.form-label for="company_address_line_2">Address Line 2</x-base.form-label>
                                    <x-base.form-input value="{{ isset($company->company_address_line_2) ? $company->company_address_line_2 : '' }}" name="company_address_line_2" id="company_address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2 (Optional)" />
                                </div>
                                <div class="grid grid-cols-12 gap-y-2 gap-x-5">
                                    <div class="col-span-12 sm:col-span-4">
                                        <x-base.form-label for="company_city">Town/City</x-base.form-label>
                                        <x-base.form-input value="{{ isset($company->company_city) ? $company->company_city : '' }}" name="company_city" id="company_city" class="w-full city" type="text" placeholder="Town/City" />
                                        <div class="acc__input-error error-company_city text-danger text-xs mt-1"></div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-4">
                                        <x-base.form-label for="company_state">Region/County</x-base.form-label>
                                        <x-base.form-input value="{{ isset($company->company_state) ? $company->company_state : '' }}" name="company_state" id="company_state" class="w-full state" type="text" placeholder="Region/County" />
                                    </div>
                                    <div class="col-span-12 sm:col-span-4">
                                        <x-base.form-label for="company_postal_code">Post Code</x-base.form-label>
                                        <x-base.form-input value="{{ isset($company->company_postal_code) ? $company->company_postal_code : '' }}" name="company_postal_code" id="company_postal_code" class="w-full postal_code" type="text" placeholder="Post Code" />
                                        <div class="acc__input-error error-company_postal_code text-danger text-xs mt-1"></div>
                                    </div>
                                </div>
                                <x-base.form-input value="{{ isset($company->company_country) ? $company->company_country : '' }}" name="company_country" id="company_country" class="w-full country" type="hidden" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="intro-y box mb-5">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Payment Terms and Bank Details</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label for="bank_name">Bank Name</x-base.form-label>
                                <x-base.form-input type="text" name="bank_name" value="{{ optional($company->companyBankDetails)->bank_name ?? '' }}"/>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label for="name_on_account">Account Name</x-base.form-label>
                                <x-base.form-input type="text" name="name_on_account" value="{{ optional($company->companyBankDetails)->name_on_account ?? '' }}"/>
                            </div>
                            <div class="col-span-12 sm:col-span-3">     
                                <x-base.form-label for="sort_code">Sort Code</x-base.form-label>
                                <x-base.form-input type="number" name="sort_code" value="{{ optional($company->companyBankDetails)->sort_code ?? '' }}"/>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label for="account_number">Account Number</x-base.form-label>
                                <x-base.form-input  type="number" name="account_number" value="{{ optional($company->companyBankDetails)->account_number ?? '' }}"/>
                            </div>
                            
                        </div>
                        <div class="mt-3">
                            <x-base.form-label for="payment_term">Payment Terms</x-base.form-label>
                            <x-base.form-textarea.index name="payment_term" rows="3"> {{ optional($company->companyBankDetails)->payment_term ?? '' }} </x-base.form-textarea.index>
                        </div>
                    </div>
                </div>
                <div class="intro-y box mb-5">
                    <div class="p-5">
                        <div class="flex flex-col items-left"> 
                            <p class="mt-0 sm:mt-2 mx-0 sm:mx-4 text-base font-medium text-center">
                                Please click on the 'upload a file' button to upload a logo for your records. 
                                If are having any issues please email your logo to our support team.
                            </p>
                        </div>
                    
                        <div class="space-y-4 flex items-center justify-center">
                            <!-- File Input -->
                            <div class="file-upload-container bg-white p-0 sm:p-6 rounded-lg w-full">
                                <div class="image-upload-wrap border-4 border-dotted border-gray-200 p-10 text-center relative cursor-pointer">
                                    <input id="fileInput" name="company_logo" class="file-upload-input absolute inset-0 w-full h-full opacity-0 cursor-pointer" type="file" accept="image/*" />
                                    <h3 class="drag-text text-gray-400 text-lg font-semibold">Drag and drop a file or select Add Image</h3>
                                    <div class="flex justify-center" id="thumbnail-preview-container">
                                        @if ($company->logo_url)
                                            <img id="thumbnail-preview" src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="h-24 w-24 border border-gray-300 rounded-md p-1 shadow-xl mt-2 hover:shadow-lg">
                                        @else   
                                            <img id="thumbnail-preview" src="{{ Vite::asset('resources/images/gas_safe_register.png') }}" alt="{{ $company->company_name }}" class="h-24 w-24 border border-gray-300 rounded-md p-1 shadow-xl mt-2 hover:shadow-lg">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div  class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-3 xl:col-span-3">
            <div  class="relative before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                <div class="p-5 box ">
                    <x-base.button type="submit" id="saveCompanyBtn" class="text-white w-full mb-3" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Save Settings
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button as="a" href="{{ route('user.settings') }}" class="w-full" variant="danger">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                        Cancel
                    </x-base.button>
                </div>
            </div>
        </div>
    </form>
</div>
@include('app.action-modals')
@endsection
@pushOnce('styles')
    @vite('resources/css/vendors/dropzone.css')
@endPushOnce
@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/dropzone.js')

@endPushOnce
@pushOnce('scripts')
  
 @vite('resources/js/app/companies.js')

@endPushOnce
