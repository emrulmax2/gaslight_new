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
        {{--<x-base.button type="button" data-customerid="{{ $customer->id }}" data-tw-toggle="modal" data-tw-target="#addCustomerJobModal" class="addCustomerJobBtn w-auto" variant="primary" >
            <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" /> Add Job
        </x-base.button>
        <x-base.menu class="w-1/2 sm:w-auto">
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
    <div class="mt-5" data-customerid="{{ $customer->id }}" data-jobid="{{ $job->id }}" id="customerJobDocListTable"></div>
</div>