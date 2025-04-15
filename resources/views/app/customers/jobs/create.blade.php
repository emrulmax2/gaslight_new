@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">New Job</h2>
        <div class="mt-0 w-auto gap-2">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <form method="post" action="#" id="addCustomerJobForm">
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-9 relative z-20">
                <div class="intro-y box">
                <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">
                            Job Details
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="mb-3">
                            <x-base.form-label for="customer_property_id">Job Address <span class="text-danger">*</span></x-base.form-label>
                            <div class="relative searchWrap" data-type="address">
                                <x-base.form-input autocomplete="off" name="search_input" class="w-full search_input address_name" type="text" placeholder="Search Address..." />
                                <x-base.form-input name="customer_property_id" id="customer_property_id" class="w-full the_id_input" type="hidden" value="0" />
                                <div class="searchResultCotainter absolute left-0 top-full shadow bg-white border rounded-md w-full z-50" style="display: none;">
                                    <div class="resultWrap">
                                        <div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>
                                    </div>

                                    <x-base.button type="button" class="addAddressBtn text-white font-bold w-full rounded-md rounded-tl-none rounded-tr-none" variant="success">
                                        <x-base.lucide class="mr-2 h-4 w-4 stroke-[1.3]" icon="plus-circle" />
                                        Add Address
                                    </x-base.button>
                                </div>
                            </div>
                            <div class="acc__input-error error-customer_property_id text-danger text-xs mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="description">Job description</x-base.form-label>
                            <x-base.form-input name="description" id="description" class="w-full cap-fullname" type="text" placeholder="Short Description" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="details">Job Details</x-base.form-label>
                            <x-base.form-textarea name="details" id="details" class="w-full h-[80px]" placeholder="Details..."></x-base.form-textarea>
                        </div>
                        <div class="grid grid-cols-12 gap-x-6 gap-y-3">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="estimated_amount">Estimated Job Value (Excluding VAT)</x-base.form-label>
                                <x-base.form-input step="any" name="estimated_amount" id="estimated_amount" class="w-full" type="number" placeholder="0.00" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="customer_job_priority_id">Priority</x-base.form-label>
                                <x-base.tom-select class="w-full" id="customer_job_priority_id" name="customer_job_priority_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($priorities->count() > 0)
                                        @foreach($priorities as $priority)
                                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            {{-- <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="due_date">Due Date</x-base.form-label>
                                <x-base.litepicker name="due_date" id="due_date" class="mx-auto block w-full" data-single-mode="true" data-format="DD-MM-YYYY" />
                            </div> --}}
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="customer_job_status_id">Job Status</x-base.form-label>
                                <x-base.tom-select class="w-full" id="customer_job_status_id" name="customer_job_status_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($statuses->count() > 0)
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>

                            
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="reference_no">Job Ref No</x-base.form-label>
                                <x-base.form-input name="reference_no" id="reference_no" class="w-full" type="text" placeholder="Reference No" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="job_calender_date">Appointment Date</x-base.form-label>
                                <x-base.form-input value="" name="job_calender_date" id="job_calender_date" class="mx-auto block w-full" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                                <div class="acc__input-error error-job_calender_date text-danger text-xs mt-1"></div>
                            </div>
                            <div class="col-span-12 sm:col-span-4 z-20 hidden calenderSlot">
                                <x-base.form-label for="calendar_time_slot_id">Slot</x-base.form-label>
                                <x-base.tom-select class="w-full" id="calendar_time_slot_id" name="calendar_time_slot_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($slots->count() > 0)
                                        @foreach($slots as $slot)
                                            <option value="{{ $slot->id }}">{{ $slot->title }} {{ $slot->start }} {{ $slot->end }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                                <div class="acc__input-error error-calendar_time_slot_id text-danger text-xs mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3 relative z-10">
                <div class="intro-y box p-5">
                    <x-base.button type="submit" id="jobSaveBtn" class="text-white w-full mb-3" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Save Job
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button as="a" href="{{ route('customer.jobs', $customer->id) }}" class="w-full" variant="danger">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                        Cancel
                    </x-base.button>
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
    @vite('resources/js/app/customers/jobs/jobs-create.js')
@endPushOnce