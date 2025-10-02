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

        <div class="settingsBox mt-5">
            <h3 class="font-medium leading-none mb-3 text-dark">Customer Information</h3>
            <div class="box rounded-md p-0 overflow-hidden">
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">
                            {{ isset($customer->customer_full_name) && !empty($customer->customer_full_name) ? $customer->customer_full_name : 'N/A' }}
                        </span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{!! $customer->full_address_with_html !!}</span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="mail" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ $customer_contact_info->email }}</span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="smartphone" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ $customer_contact_info->mobile }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Job Details
                </h2>
            </div>
            <div class="px-2 py-3 bg-white">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Address <span class="text-danger">*</span></div>
                        <div class="theDesc w-full relative">
                            <div class="relative searchWrap" data-type="address">
                                <x-base.form-input autocomplete="off" name="search_input" class="search_input address_name w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" placeholder="Search Address..." />
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
                                <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                            </div>
                        </div>
                        <div class="acc__input-error error-customer_property_id text-danger text-xs"></div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Name</div>
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
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Amount</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="estimated_amount" name="estimated_amount" value="" placeholder="0.0" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" step="any" type="number"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="badge-pound-sterling" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Appointment Date</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input readonly id="job_calender_date" name="job_calender_date" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                        </div>
                        <div class="acc__input-error error-job_calender_date text-danger text-xs"></div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2 timeSloatWrap" style="display: none;">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel relative">
                            Time Slots
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="clock" />
                        </div>
                        <div class="theDesc w-full relative">
                            @if($slots->count() > 0)
                                <div class="flex justify-start flex-wrap gap-2 mt-4 mb-2 jobCalSlot">
                                    @foreach($slots as $slt)
                                        <div class="slitItems relative">
                                            <input type="radio" name="calendar_time_slot_id" value="{{ $slt->id }}" id="calendar_time_slots_{{$slt->id}}" class="absolute opacity-0 w-0 h-0 left-0 top-0"/>
                                            <label class="inline-flex border-2 border-success rounded-full px-3 py-1.5 font-medium text-success cursor-pointer" for="calendar_time_slots_{{$slt->id}}">
                                                {{ date('H:i', strtotime($slt->start))}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="acc__input-error error-calendar_time_slot_id text-danger text-xs"></div>
                    </div>
                </div>
            </div>

            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Note</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-textarea id="details" name="details" class="w-full h-[150px] resize-none text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none"></x-base.form-textarea>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="notebook" />
                        </div>
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
                <x-base.button as="a" href="{{ route('customer.jobs', $customer->id) }}" class="w-auto" variant="danger">
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
    @vite('resources/js/app/customers/jobs/jobs-create.js')
@endPushOnce