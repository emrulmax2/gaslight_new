@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Initial Setup</title>
@endsection

@section('subcontent')
<div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
    <h2 class="mr-auto text-lg font-medium">Let’s do the initial setup</h2>
</div>
<form id="step1-form" action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="company_logo" id="company_logo" />
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-9">
            <!-- Personal Information Section -->
            <div class="intro-y box">
                <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                    <h2 class="mr-auto text-base font-medium">Company Information</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-1">
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="company_name">Organization/Company <span class="text-danger">*</span></x-base.form-label>
                                <x-base.form-input type="text" class="step1__input" placeholder="Organization/Company" name="company_name" />
                                <div id="error-company_name" class="error-company_name text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="company_phone">Phone Number <span class="text-danger">*</span></x-base.form-label>
                                <x-base.form-input type="text" placeholder="+44 123 456 7890" name="company_phone"/>
                                <div id="error-company_phone" class="error-company_phone text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 m-1">
                            <div class="mt-0 lg:mt-3 w-full flex-1 xl:mt-0">
                                <x-base.form-label for="business_type">Business Type <span class="text-danger">*</span></x-base.form-label>

                                <div class="flex items-center flex-row gap-2 justify-between">
                                    <div
                                        class="w-full whitespace-nowrap rounded-md border border-slate-300/60 bg-white px-1 lg:px-3 py-2 shadow-sm first:rounded-b-none last:-mt-px last:rounded-t-none focus:z-10 first:md:rounded-r-none first:md:rounded-bl-md last:md:-ml-px last:md:mt-0 last:md:rounded-l-none last:md:rounded-tr-md [&:not(:first-child):not(:last-child)]:-mt-px [&:not(:first-child):not(:last-child)]:rounded-none [&:not(:first-child):not(:last-child)]:md:-ml-px [&:not(:first-child):not(:last-child)]:md:mt-0">
                                        <x-base.form-check>
                                            <x-base.form-check.input id="checkbox-switch-4" type="radio" value="Sole trader" name="business_type"/>
                                            <x-base.form-check.label for="checkbox-switch-4">
                                                Sole trader
                                            </x-base.form-check.label>
                                        </x-base.form-check>
                                    </div>
                                    
                                    <div
                                        class="w-full  border border-slate-300/60 bg-white px-1 lg:px-3 py-2 shadow-sm first:rounded-b-none last:-mt-px last:rounded-t-none focus:z-10 first:md:rounded-r-none first:md:rounded-bl-md last:md:-ml-px last:md:mt-0 last:md:rounded-l-none last:md:rounded-tr-md [&:not(:first-child):not(:last-child)]:-mt-px [&:not(:first-child):not(:last-child)]:rounded-none [&:not(:first-child):not(:last-child)]:md:-ml-px [&:not(:first-child):not(:last-child)]:md:mt-0">
                                        <x-base.form-check>
                                            <x-base.form-check.input id="checkbox-switch-5" type="radio" value="Company" name="business_type"/>
                                            <x-base.form-check.label for="checkbox-switch-5">
                                                Company
                                            </x-base.form-check.label>
                                        </x-base.form-check>
                                    </div>
                                    <div
                                        class="w-full rounded-md border border-slate-300/60 bg-white px-1 lg:px-3 py-2 shadow-sm first:rounded-b-none last:-mt-px last:rounded-t-none focus:z-10 first:md:rounded-r-none first:md:rounded-bl-md last:md:-ml-px last:md:mt-0 last:md:rounded-l-none last:md:rounded-tr-md [&:not(:first-child):not(:last-child)]:-mt-px [&:not(:first-child):not(:last-child)]:rounded-none [&:not(:first-child):not(:last-child)]:md:-ml-px [&:not(:first-child):not(:last-child)]:md:mt-0">
                                        <x-base.form-check>
                                            <x-base.form-check.input id="checkbox-switch-6" type="radio" value="Other" name="business_type"/>
                                            <x-base.form-check.label for="checkbox-switch-6">
                                                Other
                                            </x-base.form-check.label>
                                        </x-base.form-check>
                                    </div>
                                </div>
                                <div id="error-business_type" class="error-business_type text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6" id="company_register_no" style="display: none;">
                            <div class="m-1">
                                <x-base.form-label for="company_registration">Registration Number <span class="text-danger">*</span></x-base.form-label>
                                <x-base.form-input type="text" placeholder="Registration Number" name="company_registration"/>
                            
                                <div id="error-company_registration" class="error-company_registration text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-y box mt-3">
                <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                    <h2 class="mr-auto text-base font-medium">Contact Details</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-y-2 theAddressWrap" id="companyAddressWrap">
                        <div class="grid grid-cols-12 gap-1">
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="customer_address_line_1">Address Lookup</x-base.form-label>
                                    <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="company_address_line_1">Address Line 1 <span class="text-danger">*</span></x-base.form-label>
                                    <x-base.form-input type="text" placeholder="123 Main Street" name="company_address_line_1"  class="address_line_1"/>
                            
                                    <div id="error-company_address_line_1" class="error-company_address_line_1 text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                                </div>
                            </div>
                            
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="company_address_line_2">Address Line 2</x-base.form-label>
                                    <x-base.form-input  type="text" placeholder="Apartment 123"  name="company_address_line_2" class="address_line_2"/>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="company_city">Town <span class="text-danger">*</span></x-base.form-label>
                                    <x-base.form-input type="text" placeholder="London" name="company_city" class="city"/>
                                    <div id="error-company_city" class="error-company_city text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="company_state">County <span class="text-danger">*</span></x-base.form-label>
                                    <x-base.form-input type="text" placeholder="London" name="company_state" class="state"/>
                                    <div id="error-company_state" class="error-company_state text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="company_state">Country <span class="text-danger">*</span></x-base.form-label>
                                    <x-base.form-input  type="text" placeholder="London" name="company_country" class="country"/>
                                    <div id="error-company_country" class="error-company_country text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="company_postal_code">Post Code <span class="text-danger">*</span></x-base.form-label>
                                    <x-base.form-input type="text" placeholder="SW1W 0NY" name="company_postal_code" class="postal_code"/>
                                     <div id="error-company_postal_code" class="error-company_postal_code text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                                </div>
                            </div>
                            <x-base.form-input name="latitude" id="latitude" class="w-full latitude" type="hidden" value="" />
                            <x-base.form-input name="longitude" id="longitude" class="w-full longitude" type="hidden" value="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-y box mt-3">
                <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                    <h2 class="mr-auto text-base font-medium">Other Information</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-1">
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="gas_safe_registration_no">Gas Safe Registration Number</x-base.form-label>
                                <x-base.form-input type="text" placeholder="Gas Safe Registration Number" name="gas_safe_registration_no" class="gas_safe_registration_no" />
                                <div id="error-gas_safe_registration_no" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="gas_safe_id_card">Gas Safe ID Card Number</x-base.form-label>
                                <x-base.form-input type="text" placeholder="Gas Safe ID Card Number" name="gas_safe_id_card" class="gas_safe_id_card"/>
                                 <div id="error-gas_safe_id_card" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 mt-4">
                            <div class="m-1">
                                <div data-tw-merge class="flex items-center">
                                    <x-base.form-switch.input class="" id="vat_number" name="vat_number" value="1" type="checkbox" />
                                    <label data-tw-merge for="vat_number" class="cursor-pointer ml-2">VAT Registered</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6 vat_number_input mt-2 hidden">
                            <div class="m-1">
                                <x-base.form-label for="company_vat">Vat Number</x-base.form-label>
                                <x-base.form-input type="text" placeholder="GB123456789" name="company_vat"/>
                                 <div id="error-company_vat" class="step1__input-error text-danger mt-0 lg:mt-2 dark:text-orange-400"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-12 box mt-2">
                <div class="gsfSignature border rounded-[3px] h-auto py-10 bg-slate-100 rounded-b-none flex justify-center items-center">
                    <x-creagia-signature-pad name='sign'
                        border-color="#e5e7eb"
                        submit-name="Save"
                        clear-name="Clear Signature"
                        submit-id="signSaveBtn"
                        clear-id="clear"
                        pad-classes="w-auto h-48 bg-white mt-0"
                    />
                    <div class="customeUploads border-2 border-dashed border-slate-500 flex items-center text-center h-[200px] max-h-[200px] sm:w-[70%] rounded-[5px] p-[20px]" style="display: none">
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
            </div>
        </div>    
        <div class="intro-y col-span-12 lg:col-span-3">
            <!-- Save and Cancel Buttons -->
            <div class="intro-y box ">
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-12">
                            <div class="flex flex-col space-y-4">
                                <x-base.button type="button" id="companySetupBtn" class="w-full text-white shadow-md" variant="success">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                    Save and Exit
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/custom/signature.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/dropzone.js')
    @vite('resources/js/vendors/sign-pad.min.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/initial-setup.js')
@endPushOnce
