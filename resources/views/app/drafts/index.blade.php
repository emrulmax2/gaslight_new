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
                <!-- <x-base.table.tr data-url="" class="recordRow intro-x box border max-sm:px-3 max-sm:pt-2 max-sm:pb-2 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tl-none sm:rounded-tl rounded-bl-none sm:rounded-bl">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Type</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">Homeowner Gas Safety Record</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Serial No</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">000001</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Inspection Name</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">Mr John Doe</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start flex-wrap">
                            <label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Inspection Address</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">87 North Gower Street, London, NW1 2NJ</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Landlord Name</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">Mr John Doe</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start flex-wrap">
                            <label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Landlord Address</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">87 North Gower Street, London, NW1 2NJ</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Assigned To</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">Alif Muhammad Chowdhury</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Created At</label>
                            <span class="text-slate-500 whitespace-normal text-xs leading-[1.3] max-sm:ml-auto">2025-04-23 11:30 AM</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tr-none sm:rounded-tr rounded-br-none sm:rounded-br">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Status</label>
                            <button class="ml-auto font-medium bg-primary rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved</button>
                        </div>
                    </x-base.table.td>
                </x-base.table.tr>
                <x-base.table.tr class="intro-x box border max-sm:px-3 max-sm:pt-2 max-sm:pb-2 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tl-none sm:rounded-tl rounded-bl-none sm:rounded-bl">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Type</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">Homeowner Gas Safety Record</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Serial No</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">000001</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Inspection Name</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">Mr John Doe</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start flex-wrap">
                            <label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Inspection Address</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">87 North Gower Street, London, NW1 2NJ</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Landlord Name</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">Mr John Doe</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start flex-wrap">
                            <label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Landlord Address</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">87 North Gower Street, London, NW1 2NJ</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Assigned To</label>
                            <span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">Alif Muhammad Chowdhury</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Created At</label>
                            <span class="text-slate-500 whitespace-normal text-xs leading-[1.3] max-sm:ml-auto">2025-04-23 11:30 AM</span>
                        </div>
                    </x-base.table.td>
                    <x-base.table.td class="border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tr-none sm:rounded-tr rounded-br-none sm:rounded-br">
                        <div class="flex items-start">
                            <label class="sm:hidden font-medium m-0">Status</label>
                            <button class="ml-auto font-medium bg-primary rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved</button>
                        </div>
                    </x-base.table.td>
                </x-base.table.tr> -->
            </x-base.table.tbody>
        </x-base.table>
    </div>

    <!-- <div class="intro-y box mt-5 p-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form class="sm:mr-auto lg:flex w-full lg:w-auto" id="tabulator-html-filter-form" >
                <div class="items-center sm:mr-4 xl:mt-0">
                    <label class="flex-none xl:w-auto xl:flex-initial max-sm:hidden">Keywords</label>
                    <x-base.form-input class="2xl:w-full h-[35px] rounded-[3px]" id="query" type="text" placeholder="Search..." />
                </div>
                <div class="items-center lg:mr-4 mt-2 lg:mt-0 max-sm:hidden">
                    <label class="flex-none xl:w-auto xl:flex-initial">Engineer</label>
                    <x-base.form-select class="mt-1 w-full sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px]" id="engineer" >
                        <option value="all">All</option>
                        @foreach($engineers as $engineer)
                            <option value="{{ $engineer->id }}">{{ $engineer->name }}</option>
                        @endforeach
                    </x-base.form-select>
                </div>
                <div class="items-center lg:mr-4 mt-2 lg:mt-0 2xl:w-64 max-sm:hidden">
                    <label class="flex-none">Certificate Type </label>
                    <x-base.form-select class="mt-1 w-auto sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px] max-w-full" id="certificate_type" >
                        <option value="all">All</option>
                        @foreach($certificate_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </x-base.form-select>
                </div>
                <div class="items-center  lg:mr-4 mt-2 lg:mt-0 w-full sm:w-56 max-sm:hidden">
                    <label class="flex-none">Date Range </label>
                    <x-base.litepicker class="mx-auto w-full h-[35px] rounded-[3px]" id="date_range"  />
                </div>
                <div class="items-center lg:mr-4 mt-2 lg:mt-0 max-sm:hidden">
                    <label class="flex-none">Status </label>
                    <x-base.form-select class="mt-1 w-full sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px]" id="status" >
                        <option value="all">All</option>
                        <option value="Draft">Draft</option>
                        <option value="Approved">Approved</option>
                        <option value="Approved & Sent">Approved & Sent</option>
                        <option value="Cancelled">Cancelled</option>
                    </x-base.form-select>
                </div>
                <div class="mt-4 lg:mt-0 text-right ml-0 sm:ml-auto xl:pt-[20px] max-sm:hidden">
                    <x-base.button class="w-full sm:w-16 h-[35px]" id="tabulator-html-filter-go" type="button" variant="primary" >Go</x-base.button>
                    <x-base.button class="mt-1 w-full sm:ml-1 sm:mt-0 sm:w-16 h-[35px]" id="tabulator-html-filter-reset" type="button" variant="secondary" >Reset</x-base.button>
                </div>
            </form>
        </div>
        <div class="scrollbar-hidden overflow-x-auto">
            <div class="mt-5 gca_responsive" id="certificateListTable" ></div>
        </div>
    </div>
    END: HTML Table Data -->

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