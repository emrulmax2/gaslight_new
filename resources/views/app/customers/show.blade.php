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
    

    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box mt-5 p-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form class="sm:mr-auto xl:flex" id="tabulator-html-filter-form" >
                <div class="mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Keywords</label>
                    <x-base.form-input class="mt-2 sm:mt-0 sm:w-40 2xl:w-full" id="query" type="text" placeholder="Search..." />
                </div>
                <div class="items-center sm:mr-4 sm:flex">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Status </label>
                    <x-base.form-select class="mt-2 w-full sm:mt-0 sm:w-auto 2xl:w-full" id="status" >
                        <option value="1">Active</option>
                        <option value="2">Archive</option>
                    </x-base.form-select>
                </div>
                <div class="mt-2 xl:mt-0">
                    <x-base.button class="w-full sm:w-16" id="tabulator-html-filter-go" type="button" variant="primary" >Go</x-base.button>
                    <x-base.button class="mt-2 w-full sm:ml-1 sm:mt-0 sm:w-16" id="tabulator-html-filter-reset" type="button" variant="secondary" >Reset</x-base.button>
                </div>
            </form>
            <div class="mt-5 flex sm:mt-0">
                <x-base.button type="button" data-customerid="{{ $customer->id }}" data-tw-toggle="modal" data-tw-target="#addCustomerJobModal" class="addCustomerJobBtn w-auto" variant="primary" >
                    <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" /> Add Job
                </x-base.button>
                {{--<x-base.menu class="w-1/2 sm:w-auto">
                    <x-base.menu.button class="w-full sm:w-auto" as="x-base.button" variant="outline-secondary" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> 
                        Export
                        <x-base.lucide class="ml-auto h-4 w-4 sm:ml-2" icon="ChevronDown" />
                    </x-base.menu.button>
                    <x-base.menu.items class="w-40">
                        <x-base.menu.item id="tabulator-export-csv">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export CSV
                        </x-base.menu.item>
                        <x-base.menu.item id="tabulator-export-xlsx">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export XLSX </x-base.menu.item>
                    </x-base.menu.items>
                </x-base.menu>--}}
            </div>
        </div>
        <div class="scrollbar-hidden overflow-x-auto">
            <div class="mt-5" data-customerid="{{ $customer->id }}" id="customerJobListTable" ></div>
        </div>
    </div>
    <!-- END: HTML Table Data -->

    @include('app.customers.show-modals')
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
    @vite('resources/js/app/customers-show.js')
    @vite('resources/js/app/jobs.js')
    @vite('resources/js/app/jobs-create.js')
@endPushOnce