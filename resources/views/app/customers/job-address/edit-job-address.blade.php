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
                        <span class="font-normal text-slate-400 text-xs block">
                            {!! $property->is_primary == 1 ? '<span class="inline-flex bg-success text-xs text-white px-2 py-0.5 rounded-sm mb-1">Default</span><br/>' : '' !!}
                            {!! (!empty($property->full_address_with_html) ? $property->full_address_with_html : 'N/A') !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="settingsBox mt-5">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-medium leading-none text-dark flex items-center">
                    <x-base.form-switch.input data-propertyid="{{ $property->id }}" checked="{{ isset($property->has_occupants) && $property->has_occupants == 1 ? 1 : 0 }}" class="mr-3 relative -top-[1px]" id="has_occupants" name="has_occupants" value="1" type="checkbox" />
                    <label data-tw-merge for="has_occupants" class="cursor-pointer font-medium mr-5">Occupants</label>
                </h3>
                <a href="javascript:void(0);" data-tw-toggle="modal" data-tw-target="#addOccupantModal" class="addOccupantToggler font-medium ml-auto text-success items-center tracking-normal" style="display: {{ isset($property->has_occupants) && $property->has_occupants != 1 ? 'none' : 'inline-flex' }};"><x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="circle-plus" /> Add Occupant</a>
            </div>
            <div class="box rounded-md p-0 overflow-hidden occupantTableWrap" style="display: {{ isset($property->has_occupants) && $property->has_occupants != 1 ? 'none' : 'block' }};">
                <div id="JobAddressOccupantsListTable" class="px-5" data-customer="{{ $customer->id }}" data-propery="{{ $property->id }}"></div>
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