@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Job Details</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('jobs.show', $job->id) }}" class="shadow-md mr-2" variant="primary">
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                Job Details
            </x-base.button>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <form method="post" action="#" id="updateJobForm">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-9">
                <div class="intro-y box p-5">
                    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start md:flex-wrap">
                        <form class="sm:mr-auto lg:flex w-full lg:w-auto" id="tabulator-html-filter-form" >
                            <input type="hidden" name="job_id" id="job_id" value="{{ $job->id }}">
                            <div class="items-center sm:mr-4 xl:mt-0 w-auto sm:w-min">
                                <label class="flex-none xl:flex-initial">Keywords</label>
                                <x-base.form-input class="w-full sm:w-auto h-[35px] rounded-[3px]" id="query" type="text" placeholder="Search..." />
                            </div>
                            <div class="items-center lg:mr-4 mt-2 lg:mt-0 sm:flex sm:flex-col sm:items-start">
                                <label class="flex-none xl:w-auto xl:flex-initial">Engineer</label>
                                <x-base.form-select class="mt-1 w-full sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px]" id="engineer" >
                                    <option value="all">All</option>
                                    @foreach($engineers as $engineer)
                                        <option value="{{ $engineer->id }}">{{ $engineer->name }}</option>
                                    @endforeach
                                </x-base.form-select>
                            </div>
                            <div class="items-center lg:mr-4 mt-2 lg:mt-0 2xl:w-64 sm:flex sm:flex-col sm:items-start">
                                <label class="flex-none">Certificate Type </label>
                                <x-base.form-select class="mt-1 w-auto sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px] max-w-full" id="certificate_type" >
                                    <option value="all">All</option>
                                    @foreach($certificate_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </x-base.form-select>
                            </div>
                            <div class="items-center  lg:mr-4 mt-2 lg:mt-0 sm:flex sm:flex-col sm:items-start">
                                <label class="flex-none">Date Range </label>
                                <x-base.litepicker class="mx-auto w-full h-[35px] rounded-[3px]" id="date_range"  />
                            </div>
                            <div class="items-center lg:mr-4 mt-2 lg:mt-0 sm:flex sm:flex-col sm:items-start">
                                <label class="flex-none">Status </label>
                                <x-base.form-select class="mt-1 w-full sm:mt-0 sm:w-auto 2xl:w-full h-[35px] rounded-[3px]" id="status" >
                                    <option value="all">All</option>
                                    <option value="Draft">Draft</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Approved & Sent">Approved & Sent</option>
                                    <option value="Cancelled">Cancelled</option>
                                </x-base.form-select>
                            </div>
                            <div class="mt-4 lg:mt-0 text-right ml-0 xl:pt-[20px]">
                                <x-base.button class="w-full sm:w-16 h-[35px]" id="tabulator-html-filter-go" type="button" variant="primary" >Go</x-base.button>
                                <x-base.button class="mt-1 w-full sm:ml-1 sm:mt-0 sm:w-16 h-[35px]" id="tabulator-html-filter-reset" type="button" variant="secondary" >Reset</x-base.button>
                            </div>
                        </form>
                    </div>
                    <div class="scrollbar-hidden overflow-x-auto">
                        <div class="mt-5 gca_responsive" id="certificateListTable" ></div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3 z-10">
                <div class="intro-y box mb-5">
                    <div class="flex flex-col items-center border-b border-slate-200/60 px-5 py-3 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">
                            Customer
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-start mb-1">
                            <x-base.lucide class="mr-3 h-4 w-4 text-success" icon="user" />
                            <span class="font-medium text-slate-500">
                                {{ $job->customer->customer_full_name }}
                            </span>
                        </div>
                        <div class="flex items-start justify-start mb-1">
                            <x-base.lucide class="mr-3 h-4 w-4 text-danger" icon="map-pin" />
                            <span class="text-slate-500">
                                {{ $job->customer->address_line_1.' '.$job->customer->address_line_2.', ' }}
                                {{ (isset($job->customer->city) && !empty($job->customer->city) ? $job->customer->city.', ' : '') }}
                                {{ (isset($job->customer->state) && !empty($job->customer->state) ? $job->customer->state.', ' : '') }}
                                {{ (isset($job->customer->postal_code) && !empty($job->customer->postal_code) ? $job->customer->postal_code.', ' : '') }}
                                {{ (isset($job->customer->country) && !empty($job->customer->country) ? $job->customer->country : '') }}
                            </span>
                        </div>
                        @if(isset($job->customer->contact->mobile) && !empty($job->customer->contact->mobile))
                        <div class="flex items-start justify-start">
                            <x-base.lucide class="mr-3 h-4 w-4 text-danger" icon="smartphone" />
                            <span class="text-slate-500">
                                {{ (isset($job->customer->contact->mobile) && !empty($job->customer->contact->mobile) ? $job->customer->contact->mobile.', ' : '') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="intro-y box mb-5">
                    <div class="flex flex-col items-center border-b border-slate-200/60 px-5 py-3 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">
                            Job Address
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-start mb-1">
                            <x-base.lucide class="mr-3 h-4 w-4 text-warning" icon="user" />
                            <span class="font-medium text-slate-500">
                                {{ (isset($job->property->customer->customer_full_name) && !empty($job->property->customer->customer_full_name) ? $job->property->customer->customer_full_name : '') }}
                            </span>
                        </div>
                        <div class="flex items-start justify-start mb-1">
                            <x-base.lucide class="mr-3 h-4 w-4 text-success" icon="map-pin" />
                            <span class="text-slate-500">
                                {{ $job->property->address_line_1.' '.$job->property->address_line_2.', ' }}
                                {{ (isset($job->property->city) && !empty($job->property->city) ? $job->property->city.', ' : '') }}
                                {{ (isset($job->property->state) && !empty($job->property->state) ? $job->property->state.', ' : '') }}
                                {{ (isset($job->property->postal_code) && !empty($job->property->postal_code) ? $job->property->postal_code.', ' : '') }}
                                {{ (isset($job->property->country) && !empty($job->property->country) ? $job->property->country : '') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- END: HTML Table Data -->

    @include('app.jobs.create-modal')
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
    @vite('resources/js/app/jobs/record-and-drafts.js')
@endPushOnce