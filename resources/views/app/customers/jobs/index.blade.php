@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Jobs</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button href="{{ route('customer.jobs.create', $customer->id) }}" as="a" type="button" class="w-auto max-sm:hidden mr-2" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" /> Add Job
            </x-base.button>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin" >
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div id="searchBox" class="intro-y box mt-5 p-0 border-none relative">
        <x-base.form-input class="m-0 w-full pl-8" id="query" type="text" autocomplete="off" placeholder="Search..."/>
        <x-base.lucide class="h-4 w-4 absolute left-2 top-0 bottom-0 m-auto text-slate-400" icon="search" />
        <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">

        <x-base.menu class="absolute right-0 top-0" id="jobStatusDropdown">
            <x-base.menu.button as="x-base.button" class="jobStatsuSelected" variant="secondary"><span class="label">Due</span><x-base.lucide class="ml-2 h-4 w-4" icon="chevron-down" /></x-base.menu.button>
            <x-base.menu.items class="w-48 jobStatsDropdown">
                <x-base.menu.item class="jobStatusBtn" data-status="All">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="circle-check" />All Jobs
                </x-base.menu.item>
                <x-base.menu.item class="jobStatusBtn active" data-status="Due">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="circle-check" />Due Jobs
                </x-base.menu.item>
                <x-base.menu.item class="jobStatusBtn" data-status="Completed">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="circle-check" />Completed Jobs
                </x-base.menu.item>
                <x-base.menu.item class="jobStatusBtn" data-status="Cancelled">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="circle-check" />Cancelled Jobs
                </x-base.menu.item>
            </x-base.menu.items>
        </x-base.menu>
    </div>

    <div class="mt-5">
        <div id="customerJobListTable">
            <a data-id="0" href="#" class="box relative jobItemWrap px-3 py-3 rounded-md block sm:flex w-full items-center mb-1 opacity-0 h-0">
                <div class="w-full sm:w-3/6">
                    <div class="font-medium text-dark leading-none mb-1 flex justify-start items-start">
                        <x-base.lucide class="stroke-1.5 mr-2 h-4 w-4 relative text-slate-500" style="top: -2px;" icon="notebook-pen" />
                        <span>Gas Warning Notice</span>
                    </div>
                    <div class="font-medium text-slate-500 leading-none mb-2 flex justify-start items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user stroke-1.5 mr-2 h-4 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Mr Registration</span>
                    </div>
                    <div class=" text-slate-500 leading-[1.2] max-sm:text-xs sm:leading-none mt-3 flex justify-start items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin stroke-1.5 mr-2 h-4 w-4 relative"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span>1854 PAISLEY ROAD WEST, GLASGOW, G52 3TW</span>
                    </div>
                </div>
                <div class="border-t sm:border-t-0 border-l-0 sm:border-l pl-0 sm:pl-5 mt-2 sm:mt-0 pt-2 sm:pt-0">
                    <div class="text-slate-500 leading-none mb-1.5 text-xs flex justify-start items-center">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="list-check" />
                        <span>Normal</span>
                    </div>
                    <div class=" text-slate-500 leading-none mb-1.5 text-xs flex justify-start items-center">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="circle-check" />
                        <span>In Progress</span>
                    </div>
                    <div class=" text-slate-500 leading-none text-xs font-medium flex justify-start items-center">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="badge-pound-sterling" />
                        <span>£0.00</span>
                    </div>
                </div>
                <div class="ml-auto max-sm:absolute max-sm:right-3 max-sm:bottom-[24px]">
                    <button data-customer="1" data-id="3" data-tw-toggle="modal" data-tw-target="#addJobCalenderModal" class="addCalenderBtn addedCalBtn border-0 rounded-[3px] bg-slate-200 text-dark p-0 w-[36px] h-[36px] inline-block text-center ml-1">
                        <span style="background: #e7bb67" class="block rounded-t-[3px] -mt-[3px] bg-success py-[1px] text-center text-white whitespace-nowrap font-medium uppercase leading-[1.2] text-[10px]">Mar</span>
                        <span style="color: #e7bb67" class="block leading-[1] pt-[5px] text-[14px] font-bold">07</span>
                    </button>
                </div>
            </a>
        </div>
    </div>

    <div class="mt-4 flex w-full sm:mt-0 sm:w-auto lg:hidden justify-center">
        <x-base.button href="{{ route('customer.jobs.create', $customer->id) }}" as="a" type="button" class="w-auto" variant="primary" >
            <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" /> Add Job
        </x-base.button>
    </div>

<!-- BEGIN: HTML Table Data
<div class="intro-y box mt-5 p-3 sm:p-5">
    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
        <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">
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
            <x-base.button href="{{ route('customer.jobs.create', $customer->id) }}" as="a" type="button" class="w-auto" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" /> Add Job
            </x-base.button>
        </div>
    </div>
    <div class="scrollbar-hidden overflow-x-auto">
        <div class="mt-3 sm:mt-5 gca_responsive" id="customerJobListTable" ></div>
    </div>

    
</div>
END: HTML Table Data -->

@include('app.customers.jobs.index-modals')
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
@vite('resources/js/app/customers/jobs/customer-jobs.js')
@endPushOnce