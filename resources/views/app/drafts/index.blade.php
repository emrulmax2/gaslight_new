@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center">
        <h2 class="mr-auto text-lg font-medium">Existing Records</h2>
        <div class="flex gap-2">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>
    
    <!-- BEGIN: HTML Table Data -->
    <div id="searchBox" class="intro-y box mt-5 p-0 border-none relative">
        <x-base.form-input class="m-0 w-full" id="query" type="text" autocomplete="off" placeholder="Search..."/>
        <x-base.lucide class="h-4 w-4 absolute right-2 top-0 bottom-0 m-auto text-slate-400" icon="search" />
    </div>

    <div class="scrollbar-hidden overflow-x-auto mt-5">
        <x-base.table class="border-separate border-spacing-y-[10px] certificateListTable" id="certificateListTable">
            <x-base.table.thead>
                <x-base.table.tr class="max-sm:hidden">
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Type
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Record No
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
                    <!-- <x-base.table.th class="whitespace-nowrap border-b-0 uppercase px-3 py-2 text-[12px] leading-none">
                        Billing Address
                    </x-base.table.th> -->
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
                    <x-base.table.td colspan="8" class="border-none px-3 py-3 rounded">
                        <div class="flex justify-center items-center text-pending">
                            No matching records found!
                        </div>
                    </x-base.table.td>
                </x-base.table.tr>
            </x-base.table.tbody>
        </x-base.table>
    </div>

    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/drafts/certificates.js')
@endPushOnce