@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center justify-between">
        <h2 class="mr-auto text-lg font-medium">Job Address</h2>
        <div class="gap-2">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>
    
    <form method="post" action="#" id="updatejobAddressForm">
        <div class="settingsBox mt-5">
            <h3 class="font-medium leading-none mb-3 text-dark">Customer Information</h3>
            <div class="box rounded-md p-0 overflow-hidden">
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">
                            {{ isset($customer->customer_full_name) && !empty($customer->customer_full_name) ? $customer->customer_full_name : 'N/A' }}
                        </span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{!! $customer->full_address_with_html !!}</span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="mail" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ $customer_contact_info->email }}</span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="smartphone" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ $customer_contact_info->mobile }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="settingsBox mt-5">
            <h3 class="font-medium leading-none mb-3 text-dark">Job Address</h3>
            <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#propertyAddressModal">
                <div class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{!! (!empty($property->full_address_with_html) ? $property->full_address_with_html : 'N/A') !!}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="settingsBox mt-5">
            <h3 class="font-medium leading-none mb-3 text-dark">Occupant's Details</h3>
            <div class="box rounded-md p-0 overflow-hidden">
                <a href="javascript:void(0);" data-type="text" data-required="0" data-title="Name" data-field="occupant_name" data-value="{{ !empty($property->occupant_name) ? $property->occupant_name : '' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">
                            {{ isset($property->occupant_name) && !empty($property->occupant_name) ? $property->occupant_name : 'N/A' }}
                        </span>
                    </div>
                </a>
                <a href="javascript:void(0);" data-type="text" data-required="0" data-title="Phone" data-field="occupant_phone" data-value="{{ !empty($property->occupant_phone) ? $property->occupant_phone : '' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  icon="smartphone" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ isset($property->occupant_phone) && !empty($property->occupant_phone) ? $property->occupant_phone : 'N/A' }}</span>
                    </div>
                </a>
                <a href="javascript:void(0);" data-type="email" data-required="0" data-title="Email" data-field="occupant_email" data-value="{{ !empty($property->occupant_email) ? $property->occupant_email : '' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="mail" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ isset($property->occupant_email) && !empty($property->occupant_email) ? $property->occupant_email : 'N/A' }}</span>
                    </div>
                </a>
                <a href="javascript:void(0);"  class="border-b flex w-full items-start px-5 py-3" data-tw-toggle="modal" data-tw-target="#updatePropertyDueDateModal">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="calendar" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ !empty($property->due_date) ? date('jS F, Y', strtotime($property->due_date)) : 'N/A' }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="settingsBox mt-5">
            <h3 class="font-medium leading-none mb-3 text-dark">Note</h3>
            <div class="box rounded-md p-0 overflow-hidden cursor-pointer" data-tw-toggle="modal" data-tw-target="#jobAddressNoteModal">
                <div class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="notebook-pen" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ !empty($property->note) ? $property->note : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </form>


    @include('app.customers.job-address.edit-job-address-modal')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/litepicker.css')
    @vite('resources/css/vendors/tabulator.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/litepicker.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/customers/job-address/job-address-edit.js')
@endPushOnce