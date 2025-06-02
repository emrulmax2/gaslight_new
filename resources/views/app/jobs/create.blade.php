@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">New Job</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <form method="post" action="#" id="addCustomerJobForm">
        <div class="intro-y box mt-5 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Job Details
                </h2>
            </div>
            <div class="px-2 py-3 bg-white">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Customer <span class="text-danger">*</span></div>
                        <div class="theDesc w-full relative">
                            <div class="relative searchWrap" data-type="customer">
                                <x-base.form-input autocomplete="off" name="search_input" class="search_input w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" placeholder="Search Customer..." />
                                <x-base.form-input name="customer_id" id="customer_id" class="w-full the_id_input" type="hidden" value="0" />
                                <div class="searchResultCotainter absolute left-0 top-full shadow bg-white border rounded-md w-full z-50" style="display: none;">
                                    <div class="resultWrap">
                                        <div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>
                                    </div>

                                    <x-base.button as="a" href="{{ route('customers.create', ['record' => 'invoice']) }}" class="text-white font-bold w-full rounded-md rounded-tl-none rounded-tr-none" variant="success">
                                        <x-base.lucide class="mr-2 h-4 w-4 stroke-[1.3]" icon="plus-circle" />
                                        Add Customer
                                    </x-base.button>
                                </div>
                            </div>
                        </div>
                        <div class="acc__input-error error-customer_property_id text-danger text-xs"></div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Address <span class="text-danger">*</span></div>
                        <div class="theDesc w-full relative">
                            <div class="relative searchWrap" data-type="address">
                                <x-base.form-input disabled autocomplete="off" name="search_input" class="search_input address_name w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none disabled:bg-white" type="text" placeholder="Search Address..." />
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
                        </div>
                        <div class="acc__input-error error-customer_property_id text-danger text-xs"></div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job description</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="description" name="description" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="notebook-pen" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Details</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="details" name="details" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="notebook" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Estimated Job Value (Excluding VAT)</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="estimated_amount" name="estimated_amount" value="" placeholder="0.0" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" step="any" type="number"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="badge-pound-sterling" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 mt-2 bg-white">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Priority</div>
                        <div class="theDesc w-full relative">
                            <x-base.tom-select id="customer_job_priority_id" name="customer_job_priority_id" data-placeholder="Please Select" class="w-full inlineTomSelect" >
                                <option value="">Please Select</option>
                                @if($priorities->count() > 0)
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                    @endforeach
                                @endif
                            </x-base.tom-select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 mt-2 bg-white">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Status</div>
                        <div class="theDesc w-full relative">
                            <x-base.tom-select id="customer_job_status_id" name="customer_job_status_id" data-placeholder="Please Select" class="w-full inlineTomSelect" >
                                <option value="">Please Select</option>
                                @if($statuses->count() > 0)
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                @endif
                            </x-base.tom-select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Ref No</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="reference_no" name="reference_no" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="hash" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Appointment Date</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="job_calender_date" name="job_calender_date" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                        </div>
                        <div class="acc__input-error error-job_calender_date text-danger text-xs"></div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 mt-2 bg-white hidden calenderSlot">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Status</div>
                        <div class="theDesc w-full relative">
                            <x-base.tom-select id="calendar_time_slot_id" name="calendar_time_slot_id" data-placeholder="Please Select" class="w-full inlineTomSelect" >
                                <option value="">Please Select</option>
                                @if($slots->count() > 0)
                                    @foreach($slots as $slot)
                                        <option value="{{ $slot->id }}">{{ $slot->title }} {{ $slot->start }} {{ $slot->end }}</option>
                                    @endforeach
                                @endif
                            </x-base.tom-select>
                        </div>
                        <div class="acc__input-error error-calendar_time_slot_id text-danger text-xs"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="intro-y box mt-2 rounded-none border-none px-2 py-2">
            <div class="flex justify-center items-center">
                <x-base.button type="submit" id="jobSaveBtn" class="text-white w-auto mr-2" variant="success">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save Job
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <x-base.button as="a" href="{{ (isset(request()->record) && !empty(request()->record) ? route('jobs', ['record' => request()->record]) :  route('jobs')) }}" class="w-auto" variant="danger">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                    Cancel
                </x-base.button>
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
    @vite('resources/js/app/jobs/create.js')
@endPushOnce