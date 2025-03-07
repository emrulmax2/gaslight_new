@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium hidden lg:block">Customer Details</h2>
        <div class="mt-4 w-full sm:mt-0 sm:w-auto hidden lg:flex gap-2">
            <x-base.button as="a" href="{{ route('customers') }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                Customer List
            </x-base.button>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
        <div class="flex w-full justify-between items-center lg:hidden">
            <h2 class="text-lg font-medium">Customer Details</h2>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <form method="post" action="#" id="customerUpdateForm">
        <div class="mt-5 grid grid-cols-12 gap-6">
            <!-- Left Column (9 columns on large devices, 12 on small devices) -->
            <div class="intro-y col-span-12 lg:col-span-9">
                <!-- Personal Information Section -->
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Personal Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-1">
                            <!-- Title Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="title_id">Title</x-base.form-label>
                                    <x-base.tom-select class="w-full" id="title_id" name="title_id" data-placeholder="Please Select">
                                        <option value="">Please Select</option>
                                        @if($titles->count() > 0)
                                            @foreach($titles as $title)
                                                <option {{ $customer->title_id == $title->id ? 'Selected' : '' }} value="{{ $title->id }}">{{ $title->name }}</option>
                                            @endforeach
                                        @endif
                                    </x-base.tom-select>
                                </div>
                            </div>
    
                            <!-- First Name Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="first_name">First Name</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->first_name) ? $customer->first_name : '' }}" name="first_name" id="first_name" class="w-full" type="text" placeholder="First Name" />
                                </div>
                            </div>
    
                            <!-- Last Name Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="last_name">Last Name <span class="text-danger">*</span></x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->last_name) ? $customer->last_name : '' }}" name="last_name" id="last_name" class="w-full" type="text" placeholder="Last Name" />
                                    <div class="acc__input-error error-last_name text-danger text-xs mt-1"></div>
                                </div>
                            </div>
    
                            <!-- Company Name Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="company_name">Company Name</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->company_name) ? $customer->company_name : '' }}" name="company_name" id="company_name" class="w-full" type="text" placeholder="Company Name" />
                                </div>
                            </div>
    
                            <!-- VAT No Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="vat_no">VAT No</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->vat_no) ? $customer->vat_no : '' }}" name="vat_no" id="vat_no" class="w-full" type="text" placeholder="VAT No" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Address Section -->
                <div class="intro-y box mt-6">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Address</h2>
                    </div>
                    <div class="p-5 theAddressWrap" id="customerAddressWrap">
                        <div class="grid grid-cols-12 gap-1">
                            <!-- Address Lookup Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="customer_address_lookup">Address Lookup</x-base.form-label>
                                    <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                                </div>
                            </div>
    
                            <!-- Address Line 1 Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="customer_address_line_1">Address Line 1</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->address_line_1) ? $customer->address_line_1 : '' }}" name="address_line_1" id="customer_address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
                                </div>
                            </div>
    
                            <!-- Address Line 2 Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="address_line_2">Address Line 2</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->address_line_2) ? $customer->address_line_2 : '' }}" name="address_line_2" id="address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2 (Optional)" />
                                </div>
                            </div>
    
                            <!-- Town/City Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="city">Town/City</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->city) ? $customer->city : '' }}" name="city" id="city" class="w-full city" type="text" placeholder="Town/City" />
                                </div>
                            </div>
    
                            <!-- Region/County Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="state">Region/County</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->state) ? $customer->state : '' }}" name="state" id="state" class="w-full state" type="text" placeholder="Region/County" />
                                </div>
                            </div>
    
                            <!-- Post Code Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="postal_code">Post Code</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->postal_code) ? $customer->postal_code : '' }}" name="postal_code" id="postal_code" class="w-full postal_code" type="text" placeholder="Post Code" />
                                </div>
                            </div>
                        </div>
                        <!-- Hidden Fields -->
                        <x-base.form-input value="{{ isset($customer->country) ? $customer->country : '' }}" name="country" id="country" class="w-full country" type="hidden" value="" />
                        <x-base.form-input value="{{ isset($customer->latitude) ? $customer->latitude : '' }}" name="latitude" id="latitude" class="w-full latitude" type="hidden" value="" />
                        <x-base.form-input value="{{ isset($customer->longitude) ? $customer->longitude : '' }}" name="longitude" id="longitude" class="w-full longitude" type="hidden" value="" />
                    </div>
                </div>
    
                <!-- Contact Information Section -->
                <div class="intro-y box mt-6">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Contact Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-1">
                            <!-- Mobile Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="mobile">Mobile</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->contact->mobile) ? $customer->contact->mobile : '' }}" name="mobile" id="mobile" class="w-full" type="text" placeholder="Mobile" />
                                </div>
                            </div>
    
                            <!-- Phone Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="phone">Phone</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->contact->phone) ? $customer->contact->phone : '' }}" name="phone" id="phone" class="w-full" type="text" placeholder="Phone" />
                                </div>
                            </div>
    
                            <!-- Email Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="email">Email</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->contact->email) ? $customer->contact->email : '' }}" name="email" id="email" class="w-full" type="email" placeholder="Email Address" />
                                </div>
                            </div>
    
                            <!-- Other Email Field -->
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="other_email">Other Email</x-base.form-label>
                                    <x-base.form-input value="{{ isset($customer->contact->other_email) ? $customer->contact->other_email : '' }}" name="other_email" id="other_email" class="w-full" type="email" placeholder="Secondary Email Address" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Note Section -->
                <div class="intro-y box mt-6">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Note</h2>
                    </div>
                    <div class="p-5">
                        <x-base.form-textarea name="note" id="note" class="w-full h-[120px]" placeholder="Note">{{ isset($customer->note) ? $customer->note : '' }}</x-base.form-textarea>
                    </div>
                </div>
            </div>
    
            <div class="intro-y col-span-12 lg:col-span-3">
                <!-- Automatic Reminder Section -->
                <div class="intro-y box">
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12">
                                <x-base.form-switch class="w-full mt-3 sm:ml-auto sm:mt-0 sm:w-auto">
                                    <x-base.form-switch.label class="ml-0 sm:ml-2" for="auto_reminder">Automatic Reminder?</x-base.form-switch.label>
                                    <x-base.form-switch.input checked class="ml-3 mr-0" id="auto_reminder" name="auto_reminder" value="1" type="checkbox" />
                                </x-base.form-switch>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Save and Cancel Buttons -->
                <div class="intro-y box mt-6">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-12 m-5">
                            <div class="flex flex-col space-y-4">
                                <x-base.button as="a" href="{{ route('customer.jobs', $customer->id) }}" class="mr-1 w-full" data-tw-dismiss="modal" type="button" variant="primary">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="flame" />Jobs
                                </x-base.button>
                                <x-base.button as="a" href="{{ route('customer.job-addresses', $customer->id) }}" class="w-full shadow-md" variant="primary">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                                    Job Address
                                </x-base.button>
                                <x-base.button type="submit" id="customerUpdateBtn" class="w-full text-white shadow-md" variant="success">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                    Update Customer
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                                <x-base.button as="a" href="{{ route('company.dashboard') }}" class="w-full shadow-md" variant="danger">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="home" />
                                    Home
                                </x-base.button>
                
                                <input type="hidden" name="id" value="{{ $customer->id }}" />
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
    @vite('resources/css/vendors/tabulator.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/customers/customers-edit.js')
@endPushOnce