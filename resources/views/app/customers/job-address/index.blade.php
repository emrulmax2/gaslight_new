@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center justify-between">
        <h2 class="mr-auto text-lg font-medium hidden lg:block">Job Address</h2>
        <div class="mt-4 w-full sm:mt-0 sm:w-auto hidden lg:flex gap-2">
            <x-base.button as="a" href="{{ route('customer.job-addresses.create', $customer->id) }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />
                Add Job Address
            </x-base.button>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
        <div class="flex w-full justify-between items-center lg:hidden">
            <h2 class="text-lg font-medium">Job Address</h2>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>
    
    <!-- BEGIN: HTML Table Data -->
    <div id="searchBox" class="intro-y box mt-5 p-0 border-none relative">
        <x-base.form-input class="m-0 w-full" id="query" type="text" autocomplete="off" placeholder="Search..."/>
        <x-base.lucide class="h-4 w-4 absolute right-2 top-0 bottom-0 m-auto text-slate-400" icon="search" />
        <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">
    </div>

    <div class="box px-5 rounded-none scrollbar-hidden overflow-x-auto mt-5">
        <div id="JobAddressListTable">
            <a data-id="38" href="http://127.0.0.1:8003/users/navigations/38" class="relative userWrap px-0 py-4 border-b border-b-slate-100 flex w-full items-center">
                <div class="mr-auto">
                    <div class="font-medium text-dark leading-none mb-1.5 flex justify-start items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user stroke-1.5 mr-2 h-4 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Mr Registration</span>
                    </div>
                    <div class=" text-slate-500 text-xs leading-none mb-3 flex justify-start items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="smartphone" class="lucide lucide-smartphone stroke-1.5 mr-2 h-3 w-4"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect><path d="M12 18h.01"></path></svg>
                        <span>01740149260</span>
                    </div>
                    <div class=" text-slate-500 leading-none flex justify-start items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" style="top: -3px;" class="lucide lucide-map-pin stroke-1.5 mr-2 h-4 w-4 relative"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span>1854 PAISLEY ROAD WEST, GLASGOW, G52 3TW</span>
                    </div>
                </div>
                <div class="ml-auto">
                    <span class="text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </span>
                </div>
            </a>
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