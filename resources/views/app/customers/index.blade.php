@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium hidden lg:block">Customers</h2>
        <div class="mt-4 w-full sm:mt-0 sm:w-auto hidden lg:flex gap-2">
            <x-base.button as="a" href="{{ route('customers.create') }}" class="shadow-md" variant="primary">
                <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />
                Add Customer
            </x-base.button>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
        <div class="flex w-full justify-between items-center lg:hidden">
            <h2 class="text-lg font-medium">Customers</h2>
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
        <div class="gca_responsive" id="customerListTable" ></div>
    </div>
    
    <div class="mt-5 flex w-full sm:w-auto lg:hidden justify-center">
        <x-base.button as="a" href="{{ route('customers.create') }}" class="shadow-md w-full" variant="primary" >
            <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />
            Add Customer
        </x-base.button>
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
    @vite('resources/js/vendors/xlsx.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/customers/customers.js')
@endPushOnce