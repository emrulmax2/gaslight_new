@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium">Customer Details</h2>
        <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
            <x-base.button as="a" href="{{ route('customers') }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                Customer List
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: Profile Info -->
    @include('app.customers.components.info')
    <!-- END: Profile Info -->
    

    <form method="post" action="#" id="customerUpdateForm">
        <div class="mt-5 grid grid-cols-12 gap-6">
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Personal Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="mb-3">
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
                        <div class="mb-3">
                            <x-base.form-label for="first_name">First Name</x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->first_name) ? $customer->first_name : '' }}" name="first_name" id="first_name" class="w-full" type="text" placeholder="First Name" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="last_name">Last Name <span class="text-danger">*</span></x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->last_name) ? $customer->last_name : '' }}" name="last_name" id="last_name" class="w-full" type="text" placeholder="Last Name" />
                            <div class="acc__input-error error-last_name text-danger text-xs mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="company_name">Company Name</x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->company_name) ? $customer->company_name : '' }}" name="company_name" id="company_name" class="w-full" type="text" placeholder="Company Name" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="vat_no">VAT No</x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->vat_no) ? $customer->vat_no : '' }}" name="vat_no" id="vat_no" class="w-full" type="text" placeholder="VAT No" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Address</h2>
                    </div>
                    <div class="p-5 theAddressWrap" id="customerAddressWrap">
                        <div class="mb-3">
                            <x-base.form-label for="customer_address_line_1">Address Lookup</x-base.form-label>
                            <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="customer_address_line_1">Address Line 1</x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->address_line_1) ? $customer->address_line_1 : '' }}" name="address_line_1" id="customer_address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="address_line_2">Address Line 2</x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->address_line_2) ? $customer->address_line_2 : '' }}" name="address_line_2" id="address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2 (Optional)" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="city">Town/City</x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->city) ? $customer->city : '' }}" name="city" id="city" class="w-full city" type="text" placeholder="Town/City" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="state">Region/County</x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->state) ? $customer->state : '' }}" name="state" id="state" class="w-full state" type="text" placeholder="Region/County" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="postal_code">Post Code</x-base.form-label>
                            <x-base.form-input value="{{ isset($customer->postal_code) ? $customer->postal_code : '' }}" name="postal_code" id="postal_code" class="w-full postal_code" type="text" placeholder="Post Code" />
                        </div>
                        <x-base.form-input value="{{ isset($customer->country) ? $customer->country : '' }}" name="country" id="country" class="w-full country" type="hidden" value="" />
                        <x-base.form-input value="{{ isset($customer->latitude) ? $customer->latitude : '' }}" name="latitude" id="latitude" class="w-full latitude" type="hidden" value="" />
                        <x-base.form-input value="{{ isset($customer->longitude) ? $customer->longitude : '' }}" name="longitude" id="longitude" class="w-full longitude" type="hidden" value="" />
                    </div>
                </div>
            </div>

            <div class="intro-y col-span-12">
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Contact Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label for="mobile">Mobile</x-base.form-label>
                                <x-base.form-input value="{{ isset($customer->contact->mobile) ? $customer->contact->mobile : '' }}" name="mobile" id="mobile" class="w-full" type="text" placeholder="Mobile" />
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label for="phone">Phone</x-base.form-label>
                                <x-base.form-input value="{{ isset($customer->contact->phone) ? $customer->contact->phone : '' }}" name="phone" id="phone" class="w-full" type="text" placeholder="Phone" />
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label for="email">Email</x-base.form-label>
                                <x-base.form-input value="{{ isset($customer->contact->email) ? $customer->contact->email : '' }}" name="email" id="email" class="w-full" type="email" placeholder="Email Address" />
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label for="other_email">Other Email</x-base.form-label>
                                <x-base.form-input value="{{ isset($customer->contact->other_email) ? $customer->contact->other_email : '' }}" name="other_email" id="other_email" class="w-full" type="email" placeholder="Secondary Email Address" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12">
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Note</h2>
                    </div>
                    <div class="p-5">
                        <x-base.form-textarea name="note" id="note" class="w-full h-[120px]" placeholder="Note">{{ isset($customer->note) ? $customer->note : '' }}</x-base.form-textarea>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12">
                <div class="box p-5">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-switch class="w-full mt-3 sm:ml-auto sm:mt-0 sm:w-auto">
                                <x-base.form-switch.label class="ml-0 sm:ml-2" for="auto_reminder">Automatic Reminder?</x-base.form-switch.label>
                                <x-base.form-switch.input checked class="ml-3 mr-0" id="auto_reminder" name="auto_reminder" value="1" type="checkbox" />
                            </x-base.form-switch>
                        </div>
                        <div class="col-span-12 sm:col-span-6 text-right">
                            <x-base.button as="a" href="{{ route('customers.jobs', $customer->id) }}" class="shadow-md" variant="danger">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                                Cancel
                            </x-base.button>
                            <x-base.button type="submit" id="customerUpdateBtn" class="text-white shadow-md" variant="success">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                Update Customer
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                            <input type="hidden" name="id" value="{{ $customer->id }}"/>
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
    @vite('resources/js/app/customers-edit.js')
@endPushOnce