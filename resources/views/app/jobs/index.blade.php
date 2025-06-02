@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Jobs</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button href="{{ route('jobs.create') }}" as="a" type="button" class="w-auto mr-2 max-sm:hidden" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" /> Add Job
            </x-base.button>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div id="searchBox" class="intro-y box mt-5 p-0 border-none relative">
        <x-base.form-input class="m-0 w-full" id="query" type="text" autocomplete="off" placeholder="Search..."/>
        <x-base.lucide class="h-4 w-4 absolute right-2 top-0 bottom-0 m-auto text-slate-400" icon="search" />
    </div>

    <div class="mt-5">
        <div data-params="{{ (isset(request()->record) && !empty(request()->record) ? request()->record : '') }}" id="jobListTable">
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
        <x-base.button href="{{ route('jobs.create') }}" as="a" type="button" class="w-auto" variant="primary" >
            <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" /> Add Job
        </x-base.button>
    </div>

    @include('app.jobs.index-modals')
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
    @vite('resources/js/app/jobs/jobs.js')
@endPushOnce