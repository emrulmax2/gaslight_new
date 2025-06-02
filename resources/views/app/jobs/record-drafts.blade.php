@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Job Details</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('jobs.show', $job->id) }}" class="shadow-md mr-2" variant="primary">
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                Job Details
            </x-base.button>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Customer Information</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ isset($job->customer->customer_full_name) && !empty($job->customer->customer_full_name) ? $job->customer->customer_full_name : 'N/A' }}
                    </span>
                </div>
            </a>
            <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{!! $job->customer->full_address_with_html !!}</span>
                </div>
            </a>
            <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="mail" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ (isset($job->customer->contact->email) ? $job->customer->contact->email : 'N/A') }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="smartphone" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->customer->contact->mobile) ? $job->customer->contact->mobile : 'N/A' }}</span>
                </div>
            </a>
        </div>
    </div>
    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Job Address</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            @if(isset($job->property->occupant_name) && !empty($job->property->occupant_name))
            <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ isset($job->property->occupant_name) && !empty($job->property->occupant_name) ? $job->property->occupant_name : 'N/A' }}
                    </span>
                </div>
            </a>
            @endif
            <a href="javascript:void(0);" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{!! $job->property->full_address_with_html !!}</span>
                </div>
            </a>
        </div>
    </div>

    <h3 class="font-medium leading-none mt-5 mb-3 text-dark">Existing Records & Drafts</h3>
    <div id="searchBox" class="intro-y box p-0 border-none relative">
        <x-base.form-input class="m-0 w-full" id="query" type="text" autocomplete="off" placeholder="Search..."/>
        <x-base.lucide class="h-4 w-4 absolute right-2 top-0 bottom-0 m-auto text-slate-400" icon="search" />
    </div>

    <div class="scrollbar-hidden overflow-x-auto mt-5">
        <x-base.table data-jobid="{{ $job->id }}" class="border-separate border-spacing-y-[10px] certificateListTable" id="certificateListTable">
            <x-base.table.thead>
                <x-base.table.tr class="max-sm:hidden">
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Type
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Serial No
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Inspection Name
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Inspection Address
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Landlord Name
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Landlord Address
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Assigned To
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Created at
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 text-right uppercase px-3 py-2 text-[12px] leading-none">
                        Status
                    </x-base.table.th>
                </x-base.table.tr>
            </x-base.table.thead>
            <x-base.table.tbody>
                <x-base.table.tr data-url="" class="intro-x box bg-pending bg-opacity-10 border border-pending border-opacity-5 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">
                    <x-base.table.td colspan="9" class="border-none px-3 py-3 rounded">
                        <div class="flex justify-center items-center text-pending">
                            No matching records found!
                        </div>
                    </x-base.table.td>
                </x-base.table.tr>
            </x-base.table.tbody>
        </x-base.table>
    </div>

    @include('app.jobs.create-modal')
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
    @vite('resources/js/app/jobs/record-and-drafts.js')
@endPushOnce