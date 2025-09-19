@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Boiler Brands</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Brand List</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('superadmin.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box mt-5 p-3 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form class="sm:mr-auto xl:flex" id="tabulator-html-filter-form" >
                <div class="mt-0 sm:mt-2 items-center sm:mr-4 sm:flex xl:mt-0">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial max-sm:hidden">Keywords</label>
                    <x-base.form-input class="max-sm:mt-2 sm:mt-0 sm:w-40 2xl:w-full" id="query" type="text" placeholder="Search..." />
                </div>
                <div class="items-center sm:mr-4 sm:flex max-sm:hidden">
                    <label class="mr-2 w-12 flex-none xl:w-auto xl:flex-initial">Status </label>
                    <x-base.form-select class="mt-2 w-full sm:mt-0 sm:w-auto 2xl:w-full" id="status" >
                        <option value="1">Active</option>
                        <option value="2">Archive</option>
                    </x-base.form-select>
                </div>
                <div class="mt-2 xl:mt-0 max-sm:hidden">
                    <x-base.button class="w-full sm:w-16" id="tabulator-html-filter-go" type="button" variant="primary" >Go</x-base.button>
                    <x-base.button class="mt-2 w-full sm:ml-1 sm:mt-0 sm:w-16" id="tabulator-html-filter-reset" type="button" variant="secondary" >Reset</x-base.button>
                </div>
            </form>
            <div class="mt-5 flex sm:mt-0 max-sm:hidden">
                <x-base.button id="add-new"  class="shadow-md add_btn" variant="primary" >
                    <x-base.lucide class="mr-2 h-4 w-4" icon="circle-plus" />
                    Add Brand
                </x-base.button>
            </div>
        </div>
        <div class="scrollbar-hidden overflow-x-auto">
            <div class="mt-3 sm:mt-5 gca_responsive" id="boilerBrandListTable" ></div>
        </div>
    </div>
    <!-- END: HTML Table Data -->

    
    @include('app.superadmin.boiler_new_brands.modal')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')

@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
    @vite('resources/js/vendors/axios.js')
@endPushOnce

@pushOnce('scripts')

    @vite('resources/js/app/boiler-new-brands/list.js')
    @vite('resources/js/app/boiler-new-brands/crud.js')
@endPushOnce
