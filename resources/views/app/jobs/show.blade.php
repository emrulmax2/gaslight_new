@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Job Details</h2>
        <div class="flex mt-0 w-auto">
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
                            <x-base.form-input value="{{ (!empty($job->description) ? $job->description : '') }}" name="description" id="description" class="w-full cap-fullname" type="text" placeholder="Short Description" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="details">Note</x-base.form-label>
                            <x-base.form-textarea name="details" id="details" class="w-full h-[80px] cap-fullname" placeholder="Details...">{{ (!empty($job->details) ? $job->details : '') }}</x-base.form-textarea>
                        </div>
                        <div class="grid grid-cols-12 gap-x-6 gap-y-3">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="estimated_amount">Estimated Job Value {{ !$hasVat ? '(Excluding VAT)' : '' }}</x-base.form-label>
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
                                            @php
                                                $time = $slot->start; // Example time in 24-hour format
                                                $dateTime = new DateTime($time);
                                                $formattedStartTime = $dateTime->format("h:i A");
                                                
                                                $time2 = $slot->end; // Example time in 24-hour format
                                                $dateTime2 = new DateTime($time2);
                                                $formattedEndTime = $dateTime2->format("h:i A");

                                            @endphp
                                            <option {{ (isset($job->calendar->calendar_time_slot_id) && $job->calendar->calendar_time_slot_id == $slot->id ? 'Selected' : '') }} value="{{ $slot->id }}">{{ $slot->title }} {{ $formattedStartTime }} - {{ $formattedEndTime }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                                <div class="acc__input-error error-calendar_time_slot_id text-danger text-xs mt-1"></div>
                            </div>
                        </div>
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
                <div class="intro-y box p-5">
                    <!-- <x-base.button type="button" class="mb-3 w-full text-white" variant="twitter" data-tw-toggle="modal" data-tw-target="#jobActionsListModal">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="plus-circle" />Create
                    </x-base.button> -->
                    <x-base.button type="submit" id="jobUpdateBtn" class="text-white w-full mb-3" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Update Job
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button as="a" href="{{ (isset(request()->record) && !empty(request()->record) ? route('jobs.record.and.drafts', ['record' => request()->record, 'job' => $job->id]) :  route('jobs.record.and.drafts', ['job' => $job->id])) }}" class="text-white w-full mb-3" variant="linkedin">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="layers" />
                        Existing Records & Drafts
                    </x-base.button>
                    <!-- <x-base.button as="a" href="{{ (isset(request()->record) && !empty(request()->record) ? route('jobs', ['record' => request()->record]) :  route('jobs')) }}" class="w-full" variant="danger">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                        Cancel
                    </x-base.button> -->
                    <x-base.form-input type="hidden" value="{{ $job->id }}" name="customer_job_id" />
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
    @vite('resources/js/app/jobs/show.js')
@endPushOnce