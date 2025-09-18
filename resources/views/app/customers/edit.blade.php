@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center">
        <h2 class="mr-auto text-lg font-medium">Customer Details</h2>
        <div class="sm:w-auto gap-2">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Personal Info</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="javascript:void(0);" data-tw-toggle="modal" data-tw-target="#customerNameModal" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ isset($customer->customer_full_name) && !empty($customer->customer_full_name) ? $customer->customer_full_name : 'N/A' }}
                    </span>
                </div>
            </a>
            <a href="javascript:void(0);" data-model="customer" data-type="text" data-required="0" data-title="Company Name" data-field="company_name" data-value="{{ $customer->company_name }}" class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="building" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($customer->company_name) ? $customer->company_name : 'N/A' }}</span>
                </div>
            </a>
            <!-- <a href="javascript:void(0);" data-model="customer" data-type="text" data-required="0" data-title="VAT Number" data-field="vat_no" data-value="{{ $customer->vat_no }}" class="fieldValueToggler flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="hash" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($customer->vat_no) ? $customer->vat_no : 'N/A' }}</span>
                </div>
            </a> -->
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Customer Address</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#customerAddressModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{!! (!empty($customer->full_address_with_html) ? $customer->full_address_with_html : 'N/A') !!}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Contact Info</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="javascript:void(0);" data-model="contact" data-type="text" data-required="0" data-title="Mobile Number" data-field="mobile" data-value="{{ isset($customer->contact->mobile) ? $customer->contact->mobile : '' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  icon="smartphone" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ isset($customer->contact->mobile) ? $customer->contact->mobile : 'N/A' }}
                    </span>
                </div>
            </a>
            <a href="javascript:void(0);" data-model="contact" data-type="text" data-required="0" data-title="Phone" data-field="phone" data-value="{{ isset($customer->contact->phone) ? $customer->contact->phone : 'N/A' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="phone" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($customer->contact->phone) ? $customer->contact->phone : 'N/A' }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-model="contact" data-type="email" data-required="0" data-title="Email" data-field="email" data-value="{{ isset($customer->contact->email) ? $customer->contact->email : 'N/A' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  icon="mail" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($customer->contact->email) ? $customer->contact->email : 'N/A' }}</span>
                </div>
            </a>
            <!-- <a href="javascript:void(0);" data-model="contact" data-type="email" data-required="0" data-title="Other Email" data-field="other_email" data-value="{{ isset($customer->contact->other_email) ? $customer->contact->other_email : 'N/A' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="mails" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($customer->contact->other_email) ? $customer->contact->other_email : 'N/A' }}</span>
                </div>
            </a> -->
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Note</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#customerNoteModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="notebook-pen" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($customer->note) ? $customer->note : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Automatic Reminder</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#reminderModal">
            <div class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="bell" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ (isset($customer->auto_reminder) && $customer->auto_reminder == 1 ? 'Yes' : 'No') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Jobs Addresses</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer">
            <a href="{{ route('customer.job-addresses', $customer->id) }}" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="map-pin" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Number of Job Addresses</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($customer->properties) && $customer->properties->count() > 0 ? $customer->properties->count() : '0' }}</span>
                </div>
            </a>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Jobs</h3>
        <div class="box rounded-md p-0 overflow-hidden cursor-pointer">
            <a href="{{ route('customer.jobs', $customer->id) }}" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  style="margin-top: 2px;" icon="briefcase" />
                <div>
                    <span class="font-medium text-slate-500 text-sm block">Number of Jobs</span>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($customer->jobs) && $customer->jobs->count() > 0 ? $customer->jobs->count() : '0' }}</span>
                </div>
            </a>
        </div>
    </div>


    @include('app.customers.edit-modal')
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