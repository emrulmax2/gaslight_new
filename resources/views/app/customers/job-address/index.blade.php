@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center justify-between">
        <h2 class="mr-auto text-lg font-medium">Job Address</h2>
        <div class="mt-4 w-full sm:mt-0 sm:w-auto hidden lg:flex">
            <x-base.button as="a" href="{{ route('customer.job-addresses.create', $customer->id) }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />
                Add Job Address
            </x-base.button>
        </div>
    </div>
    
   <!-- BEGIN: HTML Table Data -->
   <div class="intro-y box mt-5 p-5">
    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
        <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">
        <form class="sm:mr-auto lg:flex w-full lg:w-auto" id="tabulator-html-filter-form" >
            <div class="items-center sm:mr-4 sm:flex xl:mt-0 gap-3">
                <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial hidden lg:block">Keywords</label>
                <x-base.form-input class="sm:mt-0 2xl:w-full" id="query" type="text" placeholder="Search..." />
            </div>
            <div class="items-center hidden lg:mr-4 lg:flex ">
                <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Status </label>
                <x-base.form-select class="mt-2 w-full sm:mt-0 sm:w-auto 2xl:w-full" id="status" >
                    <option value="1">Active</option>
                    <option value="2">Archive</option>
                </x-base.form-select>
            </div>
            <div class="xl:mt-0 hidden lg:block">
                <x-base.button class="w-full sm:w-16" id="tabulator-html-filter-go" type="button" variant="primary" >Go</x-base.button>
                <x-base.button class="mt-2 w-full sm:ml-1 sm:mt-0 sm:w-16" id="tabulator-html-filter-reset" type="button" variant="secondary" >Reset</x-base.button>
            </div>
        </form>
    </div>
    <div class="scrollbar-hidden overflow-x-auto">
        <div class="mt-5 jalt_responsive" id="JobAddressListTable" ></div>
    </div>
    <div class="mt-4 flex w-full sm:w-auto lg:hidden justify-center">
        <x-base.button as="a" href="{{ route('customer.job-addresses.create', $customer->id) }}" class="shadow-md" variant="primary" >
            <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />
            Add Job Address
        </x-base.button>
    </div>
</div>
<!-- END: HTML Table Data -->


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
    @vite('resources/js/app/customers/job-address/job-address.js')
@endPushOnce