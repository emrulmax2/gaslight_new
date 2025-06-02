@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Job Details</h2>
        <div class="mt-0 w-auto gap-2">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Customer Information</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ isset($job->customer->customer_full_name) && !empty($job->customer->customer_full_name) ? $job->customer->customer_full_name : 'N/A' }}
                    </span>
                </div>
            </a>
            <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{!! $job->customer->full_address_with_html !!}</span>
                </div>
            </a>
            <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="mail" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ (isset($job->customer->contact->email) ? $job->customer->contact->email : '') }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="smartphone" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->customer->contact->mobile) ? $job->customer->contact->mobile : '' }}</span>
                </div>
            </a>
        </div>
    </div>
    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Job Address</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            @if(isset($job->property->occupant_name) && !empty($job->property->occupant_name))
            <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ isset($job->property->occupant_name) && !empty($job->property->occupant_name) ? $job->property->occupant_name : '' }}
                    </span>
                </div>
            </a>
            @endif
            <a href="javascript:void(0);" class="flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{!! $job->property->full_address_with_html !!}</span>
                </div>
            </a>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <h3 class="font-medium leading-none mb-3 text-dark">Job Details</h3>
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="javascript:void(0);" data-type="text" data-required="0" data-title="Description" data-field="description" data-value="{{ !empty($job->description) ? $job->description : '' }}"  class="textValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="notebook-pen" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">
                        {{ isset($job->description) && !empty($job->description) ? $job->description : 'N/A' }}
                    </span>
                </div>
            </a>
            <a href="javascript:void(0);" data-type="text" data-required="0" data-title="Details" data-field="details" data-value="{{ !empty($job->details) ? $job->details : '' }}"  class="textValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success"  icon="notebook" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->details) && !empty($job->details) ? $job->details : 'N/A' }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-type="number" data-required="0" data-title="Estimated Job Value (Excluding VAT)" data-field="estimated_amount" data-value="{{ !empty($job->estimated_amount) ? $job->estimated_amount : '' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="badge-pound-sterling" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->estimated_amount) && !empty($job->estimated_amount) ? $job->estimated_amount : 'N/A' }}</span>
                </div>
            </a>
            <a href="javascript:void(0);"  class="border-b flex w-full items-start px-5 py-3" data-tw-toggle="modal" data-tw-target="#updatePriorityModal">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="arrow-down-0-1" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->priority->name) && !empty($job->priority->name) ? $job->priority->name : 'N/A' }}</span>
                </div>
            </a>
            <a href="javascript:void(0);"  class="border-b flex w-full items-start px-5 py-3" data-tw-toggle="modal" data-tw-target="#updateStatusModal">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="circle-check-big" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->status->name) && !empty($job->status->name) ? $job->status->name : 'N/A' }}</span>
                </div>
            </a>
            <a href="javascript:void(0);" data-type="text" data-required="0" data-title="Job Ref No" data-field="reference_no" data-value="{{ !empty($job->reference_no) ? $job->reference_no : '' }}"  class="fieldValueToggler border-b flex w-full items-start px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="hash" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->reference_no) && !empty($job->reference_no) ? $job->reference_no : 'N/A' }}</span>
                </div>
            </a>
            <a href="javascript:void(0);"  class="border-b flex w-full items-start px-5 py-3" data-tw-toggle="modal" data-tw-target="#updateApointDateModal">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="calendar" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->calendar->date) && !empty($job->calendar->date) ? date('jS F, Y', strtotime($job->calendar->date)) : 'N/A' }}</span>
                </div>
            </a>
            @if(isset($job->calendar->date) && !empty($job->calendar->date))
            <a href="javascript:void(0);"  class="border-b flex w-full items-start px-5 py-3" data-tw-toggle="modal" data-tw-target="#updateApointDateModal">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="clock-alert" />
                <div>
                    <span class="font-normal text-slate-400 text-xs block">{{ isset($job->calendar->slot->slot_title) && !empty($job->calendar->slot->slot_title) ? $job->calendar->slot->slot_title : 'N/A' }}</span>
                </div>
            </a>
            @endif
        </div>
    </div>
    <!-- END: HTML Table Data -->

    @include('app.customers.jobs.show-modals')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/litepicker.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/dayjs.js')
    @vite('resources/js/vendors/litepicker.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/customers/jobs/jobs-update.js')
@endPushOnce