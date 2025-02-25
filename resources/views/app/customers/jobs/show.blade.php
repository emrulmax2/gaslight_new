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
    
    <form method="post" action="#" id="updateJobForm">
        <input type="hidden" name="customer_id" value="{{ $customer->id }}"/>
        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
        <div class="grid grid-cols-12 gap-x-6 gap-y-0 mt-5">
            <div class="col-span-12 sm:col-span-9">
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 py-3 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Job Details</h2>
                        <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
                            
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-x-5 gap-y-2">
                            <div class="col-span-12">
                                <x-base.form-label for="description">Job description</x-base.form-label>
                                <x-base.form-input value="{{ !empty($job->description) ? $job->description : '' }}" name="description" id="description" class="w-full" type="text" placeholder="Short Description" />
                            </div>
                            <div class="col-span-12">
                                <x-base.form-label for="details">Job Details</x-base.form-label>
                                <x-base.form-textarea name="details" id="details" class="w-full h-[80px]" placeholder="Details...">{{ !empty($job->details) ? $job->details : '' }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12">
                                <div class="grid grid-cols-4 gap-x-6 gap-y-3">
                                    <div class="grid_column">
                                        <x-base.form-label for="estimated_amount">Estimated Job Value</x-base.form-label>
                                        <x-base.form-input value="{{ !empty($job->estimated_amount) ? $job->estimated_amount : '' }}" step="any" name="estimated_amount" id="estimated_amount" class="w-full" type="number" placeholder="0.00" />
                                    </div>
                                    <div class="grid_column">
                                        <x-base.form-label for="customer_job_priority_id">Priority</x-base.form-label>
                                        <x-base.tom-select class="w-full" id="customer_job_priority_id" name="customer_job_priority_id" data-placeholder="Please Select">
                                            <option value="">Please Select</option>
                                            @if($priorities->count() > 0)
                                                @foreach($priorities as $priority)
                                                    <option {{ $job->customer_job_priority_id == $priority->id ? 'Selected' : '' }} value="{{ $priority->id }}">{{ $priority->name }}</option>
                                                @endforeach
                                            @endif
                                        </x-base.tom-select>
                                    </div>
                                    <div class="grid_column">
                                        <x-base.form-label for="due_date">Due Date</x-base.form-label>
                                        <x-base.litepicker value="{{ !empty($job->due_date) ? date('d-m-Y', strtotime($job->due_date)) : '' }}" name="due_date" id="due_date" class="mx-auto block w-full" data-single-mode="true" data-format="DD-MM-YYYY" />
                                    </div>
                                    <div class="grid_column">
                                        <x-base.form-label for="customer_job_status_id">Job Status</x-base.form-label>
                                        <x-base.tom-select class="w-full" id="customer_job_status_id" name="customer_job_status_id" data-placeholder="Please Select">
                                            <option value="">Please Select</option>
                                            @if($statuses->count() > 0)
                                                @foreach($statuses as $status)
                                                    <option {{ $job->customer_job_status_id == $status->id ? 'Selected' : '' }} value="{{ $status->id }}">{{ $status->name }}</option>
                                                @endforeach
                                            @endif
                                        </x-base.tom-select>
                                    </div>
                                    <div class="grid_column">
                                        <x-base.form-label for="reference_no">Job Ref No</x-base.form-label>
                                        <x-base.form-input value="{{ !empty($job->reference_no) ? $job->reference_no : '' }}" name="reference_no" id="reference_no" class="w-full" type="text" placeholder="Reference No" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3">
                <div class="intro-y box mb-5">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 py-3 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Job Address</h2>
                    </div>
                    <div class=" p-5">
                        <div class="flex justify-start items-start">
                            <div class="inline-flex items-center justify-center bg-slate-100 border rounded-full mr-5 w-[40px] h-[40px]"><x-base.lucide class="h-4 w-4" icon="map-pin" /></div>
                            <span class="font-medium">
                                {{ $job->property->address_line_1.' '.$job->property->address_line_2.', ' }}<br/>
                                {{ !empty($job->property->city) ? $job->property->city.', ' : ''}}
                                {{ !empty($job->property->state) ? $job->property->state.', ' : ''}}
                                {{ !empty($job->property->postal_code) ? $job->property->postal_code.', ' : ''}}<br/>
                                {{ !empty($job->property->country) ? $job->property->country : ''}}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="intro-y box p-5">
                    <x-base.button type="button" class="mb-3 w-full text-white" variant="primary" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="calendar-plus" />Add Appointment
                    </x-base.button>
                    <x-base.button type="button" class="mb-3 w-full text-white" variant="twitter" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />Create
                    </x-base.button>
                    <x-base.button type="button" class="w-full text-white" variant="linkedin" data-tw-toggle="modal" data-tw-target="#jobUploadDocModal">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="upload-cloud" />Uploads
                    </x-base.button>
                </div>
                <div class="intro-y py-3">
                    <x-base.button type="submit" id="jobUpdateBtn" class="w-full" variant="outline-success" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Update Job
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                </div>
            </div>
        </div>
    </form>
    <div class="intro-y box mt-5">
        <x-base.tab.group>
            <x-base.tab.list variant="tabs" class="flex items-start justify-start border-b border-slate-200/60 p-5 py-3 dark:border-darkmode-400 sm:flex-row">
                <x-base.tab id="job-history-tab" class="flex-none ml-2" selected >
                    <x-base.tab.button class="w-full py-2 border-0 rounded-md font-medium bg-slate-100 text-primary inline-flex justify-center items-center [&.active]:bg-primary [&.active]:text-white" as="button" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="list" />
                        History
                    </x-base.tab.button>
                </x-base.tab>
                @if($job->documents->count() > 0)
                <x-base.tab id="job-document-tab" class="flex-none ml-2">
                    <x-base.tab.button class="w-full py-2 border-0 rounded-md font-medium bg-slate-100 text-primary inline-flex justify-center items-center [&.active]:bg-primary [&.active]:text-white" as="button" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="file" />
                        Documents
                    </x-base.tab.button>
                </x-base.tab>
                @endif
            </x-base.tab.list>

            <x-base.tab.panels class="border-b border-l border-r">
                <x-base.tab.panel class="p-5 leading-relaxed" id="job-history" selected >
                    History......
                </x-base.tab.panel>
                @if($job->documents->count() > 0)
                <x-base.tab.panel class="p-5 leading-relaxed" id="job-document" >
                    Documents.....
                </x-base.tab.panel>
                @endif
            </x-base.tab.panels>
        </x-base.tab.group>
        {{--<div class="flex flex-col items-center border-b border-slate-200/60 p-5 py-3 dark:border-darkmode-400 sm:flex-row">
            <x-base.button type="button" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="list" />
                History
            </x-base.button>
            @if($job->documents->count() > 0)
            <x-base.button as="a" class="ml-2" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="file" />
                Documents
            </x-base.button>
            @endif
        </div>
        <div class="p-5">

        </div>--}}
    </div>

    @include('app.customers.jobs.show-modals')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/vendors/dropzone.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/dropzone.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/jobs-show.js')
    @vite('resources/js/app/job-uploads.js')
@endPushOnce