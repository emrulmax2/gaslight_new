@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium hidden lg:block">Job Details</h2>
        <div class="mt-0 w-auto hidden lg:flex gap-2">
            <x-base.button as="a" href="{{ route('customer.jobs', $job->customer->id) }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                Job List
            </x-base.button>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
        <div class="flex w-full justify-between items-center lg:hidden">
            <h2 class="text-lg font-medium">Job Details</h2>
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <form method="post" action="#" id="updateJobForm">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-9">
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">
                            Job Details
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="mb-3">
                            <x-base.form-label for="description">Job description</x-base.form-label>
                            <x-base.form-input value="{{ (!empty($job->description) ? $job->description : '') }}" name="description" id="description" class="w-full" type="text" placeholder="Short Description" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="details">Job Details</x-base.form-label>
                            <x-base.form-textarea name="details" id="details" class="w-full h-[80px]" placeholder="Details...">{{ (!empty($job->details) ? $job->details : '') }}</x-base.form-textarea>
                        </div>
                        <div class="grid grid-cols-12 gap-x-6 gap-y-3">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="estimated_amount">Estimated Job Value (Excluding VAT)</x-base.form-label>
                                <x-base.form-input value="{{ (!empty($job->estimated_amount) ? $job->estimated_amount : '') }}" step="any" name="estimated_amount" id="estimated_amount" class="w-full" type="number" placeholder="0.00" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="customer_job_priority_id">Priority</x-base.form-label>
                                <x-base.tom-select class="w-full" id="customer_job_priority_id" name="customer_job_priority_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($priorities->count() > 0)
                                        @foreach($priorities as $priority)
                                            <option {{ ($job->customer_job_priority_id == $priority->id ? 'Selected' : '') }} value="{{ $priority->id }}">{{ $priority->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            {{-- <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="due_date">Due Date</x-base.form-label>
                                <x-base.litepicker value="{{ (!empty($job->due_date) ? date('d-m-Y', strtotime($job->due_date)) : '') }}" name="due_date" id="due_date" class="mx-auto block w-full" data-single-mode="true" data-format="DD-MM-YYYY" />
                            </div> --}}
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="customer_job_status_id">Job Status</x-base.form-label>
                                <x-base.tom-select class="w-full" id="customer_job_status_id" name="customer_job_status_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($statuses->count() > 0)
                                        @foreach($statuses as $status)
                                            <option {{ ($job->customer_job_status_id == $status->id ? 'Selected' : '') }} value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="reference_no">Job Ref No</x-base.form-label>
                                <x-base.form-input value="{{ (!empty($job->reference_no) ? $job->reference_no : '') }}" name="reference_no" id="reference_no" class="w-full" type="text" placeholder="Reference No" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="job_calender_date">Appointment Date</x-base.form-label>
                                <x-base.form-input value="{{ (!empty($job->calendar->date) ? date('d-m-Y', strtotime($job->calendar->date)) : '') }}" name="job_calender_date" id="job_calender_date" class="mx-auto block w-full" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                            </div>
                            <div class="col-span-12 sm:col-span-4 z-20 calenderSlot {{ (!empty($job->calendar->calendar_time_slot_id) ? '' : 'hidden') }}">
                                <x-base.form-label for="calendar_time_slot_id">Slot</x-base.form-label>
                                <x-base.tom-select class="w-full" id="calendar_time_slot_id" name="calendar_time_slot_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($slots->count() > 0)
                                        @foreach($slots as $slot)
                                            <option {{ (isset($job->calendar->calendar_time_slot_id) && $job->calendar->calendar_time_slot_id == $slot->id ? 'Selected' : '') }} value="{{ $slot->id }}">{{ $slot->title }} {{ $slot->start }} {{ $slot->end }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                                <div class="acc__input-error error-calendar_time_slot_id text-danger text-xs mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3">
                <div class="intro-y box mb-5">
                    <div class="flex flex-col items-center border-b border-slate-200/60 px-5 py-3 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">
                            Customer
                        </h2>
                    </div>
                    <div class="p-5">
                        <dib class="flex items-start justify-start mb-1">
                            <x-base.lucide class="mr-3 h-4 w-4 text-success" icon="user" />
                            <span class="font-medium text-slate-500">
                                {{ $job->customer->full_name }}
                            </span>
                        </dib>
                        <dib class="flex items-start justify-start mb-1">
                            <x-base.lucide class="mr-3 h-4 w-4 text-danger" icon="map-pin" />
                            <span class="text-slate-500">
                                {{ $job->customer->address_line_1.' '.$job->customer->address_line_2.', ' }}
                                {{ (isset($job->customer->city) && !empty($job->customer->city) ? $job->customer->city.', ' : '') }}
                                {{ (isset($job->customer->state) && !empty($job->customer->state) ? $job->customer->state.', ' : '') }}
                                {{ (isset($job->customer->postal_code) && !empty($job->customer->postal_code) ? $job->customer->postal_code.', ' : '') }}
                                {{ (isset($job->customer->country) && !empty($job->customer->country) ? $job->customer->country : '') }}
                            </span>
                        </dib>
                        <dib class="flex items-start justify-start">
                            <x-base.lucide class="mr-3 h-4 w-4 text-danger" icon="smartphone" />
                            <span class="text-slate-500">
                                {{ (isset($job->customer->contact->mobile) && !empty($job->customer->contact->mobile) ? $job->customer->contact->mobile.', ' : '') }}
                            </span>
                        </dib>
                    </div>
                </div>
                <div class="intro-y box mb-5">
                    <div class="flex flex-col items-center border-b border-slate-200/60 px-5 py-3 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">
                            Job Address
                        </h2>
                    </div>
                    <div class="p-5">
                        <dib class="flex items-start justify-start mb-1">
                            <x-base.lucide class="mr-3 h-4 w-4 text-warning" icon="user" />
                            <span class="font-medium text-slate-500">
                                {{ (isset($job->property->customer->full_name) && !empty($job->property->customer->full_name) ? $job->property->customer->full_name : '') }}
                            </span>
                        </dib>
                        <dib class="flex items-start justify-start mb-1">
                            <x-base.lucide class="mr-3 h-4 w-4 text-success" icon="map-pin" />
                            <span class="text-slate-500">
                                {{ (isset($job->property->address_line_1) && !empty($job->property->address_line_1) ? $job->property->address_line_1.' '.$job->property->address_line_2.', ' : '') }}
                                {{ (isset($job->property->city) && !empty($job->property->city) ? $job->property->city.', ' : '') }}
                                {{ (isset($job->property->state) && !empty($job->property->state) ? $job->property->state.', ' : '') }}
                                {{ (isset($job->property->postal_code) && !empty($job->property->postal_code) ? $job->property->postal_code.', ' : '') }}
                                {{ (isset($job->property->country) && !empty($job->property->country) ? $job->property->country : '') }}
                            </span>
                        </dib>
                    </div>
                </div>
                <div class="intro-y box p-5">
                    <x-base.button type="button" class="mb-3 w-full text-white" variant="primary" >
                        <x-base.lucide class="mr-2 h-4 w-4" icon="calendar-plus" />Add Appointment
                    </x-base.button>
                    <x-base.button type="button" class="mb-3 w-full text-white" variant="twitter" data-tw-toggle="modal" data-tw-target="#jobActionsListModal">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />Create
                    </x-base.button>
                    <x-base.button type="submit" id="jobUpdateBtn" class="text-white w-full mb-3" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Update Job
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button as="a" href="{{ route('company.dashboard') }}" class="w-full" variant="danger">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="home" />
                        Home
                    </x-base.button>
                    
                    <x-base.form-input type="hidden" value="{{ $job->id }}" name="customer_job_id" />
                    <x-base.form-input type="hidden" value="{{ $job->customer->id }}" name="customer_id" />
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
    @vite('resources/js/app/customers/jobs/jobs-update.js')
@endPushOnce