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

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Company Information</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#companyInformationModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Name</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ $company->company_name }}</span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Gas Safe Registration No</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->gas_safe_registration_no) ? $company->gas_safe_registration_no : 'N/A') }}</span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Bysiness Type</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->business_type) ? $company->business_type : 'N/A') }}</span>
                </div>
            </div>
            @if(isset($company->business_type) && $company->business_type == 'Company')
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Company Registration Number</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->company_registration) ? $company->company_registration : 'N/A') }}</span>
                </div>
            </div>
            @endif
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="globe" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Company Website</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->company_web_site) ? $company->company_web_site : 'N/A') }}</span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="tags" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Company Tagline</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->company_tagline) ? $company->company_tagline : 'N/A') }}</span>
                </div>
            </div>
            <div class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Display company name on certificates?</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (isset($company->display_company_name) && $company->display_company_name == 1 ? 'Yes' : 'No') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Vat Register</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#companyVATModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <!-- <span class="font-medium text-slate-500 text-sm block">Vat Registered</span> -->
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->vat_number) ? $company->vat_number : 'Not Registered for VAT') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Registered Details</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#companyRegistrationModal">
            
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Registration No</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->registration_no) ? $company->registration_no : 'N/A') }}</span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Registration Body For</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (isset($company->regbody->name) && !empty($company->regbody->name) ? $company->regbody->name : 'N/A') }}</span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Registration Body For Legionella Risk Assessment</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->registration_body_for_legionella) ? $company->registration_body_for_legionella : 'N/A') }}</span>
                </div>
            </div>
            <div class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Registration No For Legionella Risk Assessment</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->registration_body_no_for_legionella) ? $company->registration_body_no_for_legionella : 'N/A') }}</span>
                </div>
            </div>
        </div>
    </div> -->

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Contact Details</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#companyContactModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="phone" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Phone Number</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->company_phone) ? $company->company_phone : 'N/A') }}</span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="mail" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Admin Email</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ (!empty($company->company_email) ? $company->company_email : 'N/A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Company Address</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#companyAddressModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="map-pin" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Address</span>
                    <span class="font-normal text-slate-400 text-xs block">{!! (!empty($company->full_address_with_html) ? $company->full_address_with_html : 'N/A') !!}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Bank Details</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#companyBankModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Account Name</span>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ optional($company->companyBankDetails)->name_on_account ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Sort Code</span>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ optional($company->companyBankDetails)->sort_code ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Account No</span>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ optional($company->companyBankDetails)->account_number ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Payment Terms</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ optional($company->companyBankDetails)->payment_term ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Quote & Invoice Settings</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#quoteExpireDayModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="check-circle" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Quote Expir In Days</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ $company->quote_expired_in ?? 0 }} Days</span>
                </div>
            </div>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Company Logo</h3>
        <form method="post" action="{{ route('company.update.company.logo') }}" enctype="multipart/form-data" class="relative">
            @csrf
            <input type="file" accept="image/*" onchange="form.submit()" id="company_logo" name="company_logo" value="" class="w-0 h-0 opacity-0 absolute left-0 top-0"/>
            <label for="company_logo" class="box rounded-md p-0 overflow-hidden cursor-pointer bg-white block w-full">
                <span class="flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="image" />
                    <span>
                        <span class="font-medium text-slate-500 text-sm block">Logo</span>
                        <span class="font-normal text-slate-400 text-xs mt-2 block">
                            @if ($company->logo_url)
                                <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="h-14 w-auto ">
                            @else   
                                <img src="{{ Vite::asset('resources/images/gas_safe_register.png') }}" alt="{{ $company->company_name }}" class="h-14 w-auto">
                            @endif
                        </span>
                    </span>
                </span>
            </label>
            <input type="hidden" name="company_id" value="{{ $company->id }}"/>
        </form>
    </div>

    @include('app.company.modal')
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
