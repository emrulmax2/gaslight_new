@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>User Settings</title>
@endsection

@section('subcontent')
<div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
    <h2 class="mr-auto text-lg font-medium">Company Setting</h2>
    <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
        <x-base.button as="a" href="{{ route('user.settings') }}" class="shadow-md" variant="primary" >
            <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
            Back Setting
        </x-base.button>
    </div>
</div>
<form method="post" action="#" id="companyStoreForm" enctype="multipart/form-data">
    <div class="grid grid-cols-12 gap-x-6 gap-y-10 mt-5">
        <div  class="flex flex-col col-span-12 gap-x-6 gap-y-10 md:col-span-9 xl:col-span-9">
            <div  class="relative before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                <div class="intro-y box mb-3">
                    <!-- Header -->
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Company Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <x-base.form-label for="company_name">Company Name</x-base.form-label>
                                <x-base.form-input type="text" id="company_name" name="company_name" value="{{ isset($company->company_name) ? $company->company_name : '' }}" />
                            </div>
                            <div>
                                <x-base.form-label for="company_registration">Company Registration Number</x-base.form-label>
                                <x-base.form-input type="text" id="company_registration" name="company_registration"  value="{{ isset($company->company_registration) ? $company->company_registration : '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="vat_number">VAT Number</x-base.form-label>
                                <x-base.form-input id="vat_number" type="text" name="vat_number" value="{{ isset($company->vat_number) ? $company->vat_number : '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="business_type">Company Business Type</x-base.form-label>
                                <x-base.tom-select id="business_type" name="business_type" class="w-full">
                                    <option value="1" {{ isset($company->business_type) && $company->business_type == '1' ? 'selected' : '' }}>Company</option>
                                    <option value="2" {{ isset($company->business_type) && $company->business_type == '2' ? 'selected' : '' }}>Sole Trader</option>
                                </x-base.tom-select>
                            </div>
                        </div>
                
                        <!-- Display Company Name Checkbox -->
                        <div class="col-span-12 mt-4">
                            <div class="mb-3">
                                <div class="inline-flex items-center">
                                    <input type="hidden" name="display_company_name" value="0">
                                    <label class="flex items-center cursor-pointer relative" for="check-display_company_name">
                                        <input type="checkbox" name="display_company_name" value="1" id="check-display_company_name" class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-gray-100 checked:bg-blue-500 checked:border-blue-800"
                                            {{ isset($company->display_company_name) && $company->display_company_name == 1 ? 'checked' : '' }} />
                                            <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                    </label>
                                    <label class="cursor-pointer ml-2 text-slate-600 text-sm" for="check-display_company_name">
                                        Display company name on certificates?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="intro-y box mb-3">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Registered Details</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <x-base.form-label for="gas_safe_registration_no">Gas Safe Registration No</x-base.form-label>
                                <x-base.form-input type="text" name="gas_safe_registration_no"  value="{{ isset($company->gas_safe_registration_no) ? $company->gas_safe_registration_no : '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="registration_no">Registration No</x-base.form-label>
                                <x-base.form-input type="text" name="registration_no" value="{{ isset($company->registration_no) ? $company->registration_no : '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="registration_body_for">Registration Body For</x-base.form-label>
                                <x-base.tom-select  name="registration_body_for" class="w-full" >
                                    <option value="1" {{ $company->registration_body_for == '1' ? 'selected' : ''}}>OFTEC</option>
                                    <option value="2" {{ $company->registration_body_for == '2' ? 'selected' : ''}}>AETEC</option>
                                </x-base.tom-select>
                            </div>
                            <div>
                                <x-base.form-label for="registration_body_for_legionella">Registration Body For Legionella Risk Assessment</x-base.form-label>
                                <x-base.form-input type="text" name="registration_body_for_legionella" value="{{ isset($company->registration_body_for_legionella) ? $company->registration_body_for_legionella : '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="registration_body_no_for_legionella">Registration No For Legionella Risk Assessment</x-base.form-label>
                                <x-base.form-input  type="text" name="registration_body_no_for_legionella" value="{{ isset($company->registration_body_no_for_legionella) ? $company->registration_body_no_for_legionella : '' }}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y box mb-3">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Contact Details</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <x-base.form-label for="building_or_no">Building or No</x-base.form-label>
                                <x-base.form-input type="text" name="building_or_no" value="{{ isset($company->building_or_no) ? $company->building_or_no : '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="company_address_line_1">Street Address</x-base.form-label>
                                <x-base.form-input type="text" name="company_address_line_1" value="{{ isset($company->company_address_line_1) ? $company->company_address_line_1 : '' }}" />
                            </div>
                            
                            <div>
                                <x-base.form-label for="company_city">Town/City</x-base.form-label>
                                <x-base.form-input  type="text" name="company_city" value="{{ isset($company->company_city) ? $company->company_city : '' }}"/>
                            </div>
                            <div>    
                                <x-base.form-label for="company_country">Region or Country</x-base.form-label>
                                <x-base.form-input type="text" name="company_country" value="{{ isset($company->company_country) ? $company->company_country : '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="company_postal_code">Postcode</x-base.form-label>
                                <x-base.form-input  type="text" name="company_postal_code" value="{{ isset($company->company_postal_code) ? $company->company_postal_code : '' }}"/>
                            </div>
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
                <div class="intro-y box mb-3">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Payment Terms and Bank Details</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <x-base.form-label for="bank_name">Bank Name</x-base.form-label>
                                <x-base.form-input type="text" name="bank_name" value="{{ optional($company->companyBankDetails)->bank_name ?? '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="name_on_account">Name on Account</x-base.form-label>
                                <x-base.form-input type="text" name="name_on_account" value="{{ optional($company->companyBankDetails)->name_on_account ?? '' }}"/>
                            </div>
                            
                            <div>     
                                <x-base.form-label for="sort_code">Sort Code</x-base.form-label>
                                <x-base.form-input type="text" name="sort_code" value="{{ optional($company->companyBankDetails)->sort_code ?? '' }}"/>
                            </div>
                            <div>
                                <x-base.form-label for="account_number">Account Number</x-base.form-label>
                                <x-base.form-input  type="text" name="account_number" value="{{ optional($company->companyBankDetails)->account_number ?? '' }}"/>
                            </div>
                            
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-3">
                            <div>
                                <x-base.form-label for="payment_term">Payment Terms</x-base.form-label>
                                <x-base.form-textarea.index name="payment_term" cols="30" rows="3"> {{ optional($company->companyBankDetails)->payment_term ?? '' }} </x-base.form-textarea.index>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y box mb-3">
                    <div class="p-5">
                        <div class="flex flex-col items-left"> 
                            <p class="mt-2 mx-4 text-base font-medium text-left"></p>
                            Please click on the 'upload a file' button to upload a logo for your records. If are having any issues please email your logo to support@gasengineersoftware.co.uk and we will
                            </hr>
                        </div>
                    
                        <div class="space-y-4 flex items-center justify-center">
                            <!-- File Input -->
                            <div class="file-upload-container bg-white p-6 rounded-lg w-full">
                                <div class="image-upload-wrap border-4 border-dotted border-gray-200 p-10 text-center relative cursor-pointer">
                                    <input id="fileInput" name="company_logo" class="file-upload-input absolute inset-0 w-full h-full opacity-0 cursor-pointer" type="file" accept="image/*" />
                                    <h3 class="drag-text text-gray-400 text-lg font-semibold">Drag and drop a file or select Add Image</h3>
                                    <div class="flex justify-center" id="thumbnail-preview-container">
                                        @if ($company->company_logo)
                                        <img id="thumbnail-preview" src="{{ asset($company->company_logo) }}" alt="Company Logo"
                                        class="h-24 w-24 border border-gray-300 rounded-md p-1 shadow-xl mt-2 hover:shadow-lg">
                                        @else   
                                        <img id="thumbnail-preview" src="{{ Vite::asset('resources/images/gas_safe_register.png') }}" alt="Company Logo" class="h-24 w-24 border border-gray-300 rounded-md p-1 shadow-xl mt-2 hover:shadow-lg">
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
