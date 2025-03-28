@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $form->name }}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin"><x-base.lucide class="h-4 w-4" icon="home" /></x-base.button>
        </div>
    </div>
    <div class="form-wizard">
        <div class="grid grid-cols-12 gap-5 mt-5">
            <div class="col-span-12 sm:col-span-3">
                <div class="intro-y box p-5 max-sm:hidden">
                    <div class="form-wizard-header">
                        <div class="form-wizard-steps wizard">
                            <x-base.button type="button" data-appliance="0" data-id="step_1" class="form-wizard-step-item relative pr-[25px] pl-[30px] active w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success">
                                <x-base.lucide class="w-3.5 h-3.5 mr-2 text-success absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>
                                Job Address Details <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_2" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success">
                                <x-base.lucide class="w-3.5 h-3.5 mr-2 text-success absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>    
                                Customer Details <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button data-title="Appliance" type="button" data-appliance="1" data-id="step_3" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gbscca1->id) && $gbscca1->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gbscca1->id) && $gbscca1->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/> 
                                <span class="info">
                                    @if(isset($gbscca1->make->name) && !empty($gbscca1->make->name))
                                        {{ (isset($gbscca1->make->name) && !empty($gbscca1->make->name) ? $gbscca1->make->name.' ' : '') }}
                                    @else
                                        Appliance
                                    @endif
                                </span>
                                <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_4" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gbscc->has_signatures) && $gbscc->has_signatures ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gbscc->has_signatures) && $gbscc->has_signatures ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>  
                                Signatures <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-9">
                <fieldset id="step_1" class="wizard-fieldset intro-y box show mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium inline-flex items-center">
                            <x-base.lucide class="w-4 h-4 text-success mr-2" icon="check-circle"/>
                            Job Address Details
                        </h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="jobAddressDetailsForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5 theAddressWrap" id="properyAddressWrap">
                            <div class="col-span-12">
                                <x-base.form-label class="mb-1">Address Lookup</x-base.form-label>
                                <x-base.form-input name="job_address_lookup" id="job_address_lookup" class="w-full theAddressLookup h-[35px] rounded-[3px]" type="text" placeholder="Search address here..." />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Address Line 1</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->property->address_line_1) ? $job->property->address_line_1 : '') }}" name="job_address_line_1" class="w-full address_line_1 h-[35px] rounded-[3px]" type="text" placeholder="Address Line 1" />
                                <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Address Line 2</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->property->address_line_2) ? $job->property->address_line_2 : '') }}" name="job_address_line_2" class="w-full address_line_2 h-[35px] rounded-[3px]" type="text" placeholder="Address Line 2 (Optional)" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Town/City</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->property->city) ? $job->property->city : '') }}" name="job_city" class="w-full city h-[35px] rounded-[3px]" type="text" placeholder="Town/City" />
                                <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Region/County</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->property->state) ? $job->property->state : '') }}" name="job_state" class="w-full state h-[35px] rounded-[3px]" type="text" placeholder="Region/County" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Post Code</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->property->postal_code) ? $job->property->postal_code : '') }}" name="job_postal_code" class="w-full postal_code h-[35px] rounded-[3px]" type="text" placeholder="Post Code" />
                                <div class="acc__input-error error-postal_code text-danger text-xs mt-1"></div>
                            </div>
                            <x-base.form-input name="job_country" id="country" class="w-full country" type="hidden" value="{{ (isset($job->property->country) ? $job->property->country : '') }}" />
                            <x-base.form-input name="job_latitude" class="w-full latitude" type="hidden" value="{{ (isset($job->property->latitude) ? $job->property->latitude : '') }}" />
                            <x-base.form-input name="job_longitude" class="w-full longitude" type="hidden" value="{{ (isset($job->property->longitude) ? $job->property->longitude : '') }}" />

                            <div class="col-span-12">
                                <div class="border-t border-slate-200 mb-4 mt-4"></div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="occupant_name">Occupant's Name</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->property->occupant_name) ? $job->property->occupant_name : '') }}" name="occupant_name" id="occupant_name" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Name" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="occupant_phone">Occupant's Phone</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->property->occupant_phone) ? $job->property->occupant_phone : '') }}" name="occupant_phone" id="occupant_phone" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Phone" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label for="occupant_email">Occupant's Email</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->property->occupant_email) ? $job->property->occupant_email : '') }}" name="occupant_email" id="occupant_email" class="w-full h-[35px] rounded-[3px]" type="email" placeholder="Email Address" />
                            </div>
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="0" class="form-wizard-previous-btn" variant="secondary">
                                <x-base.lucide class="h-5 w-5 mr-2" icon="move-left"/>Previous
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" class="form-wizard-next-btn ml-auto" variant="linkedin" >
                                Save & Continue<x-base.lucide class="theIcon h-5 w-5 ml-2" icon="move-right"/>
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        </div>
                    </form>
                </fieldset>
                <fieldset id="step_2" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium inline-flex items-center">
                            <x-base.lucide class="w-4 h-4 text-success mr-2" icon="check-circle"/>
                            Customer Details
                        </h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="customerDetailsForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5 theAddressWrap" id="customerAddressWrap">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Name</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->full_name) ? $job->customer->full_name : '') }}" name="customer_name" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Customer Full Name" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Company</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->company_name) ? $job->customer->company_name : '') }}" name="customer_company" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Customer Company" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Phone Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->contact->phone) ? $job->customer->contact->phone : '') }}" name="customer_phone" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Customer Phone Number"/>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Email</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->contact->customer_email) ? $job->customer->contact->customer_email : '') }}" name="customer_email" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Customer email"/>
                            </div>

                            <div class="col-span-12">
                                <div class="border-t border-slate-200 mb-4 mt-4"></div>
                            </div>

                            <div class="col-span-12">
                                <x-base.form-label class="mb-1">Address Lookup</x-base.form-label>
                                <x-base.form-input name="customer_address_lookup" id="customer_address_lookup" class="w-full theAddressLookup h-[35px] rounded-[3px]" type="text" placeholder="Search address here..." />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Address Line 1</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->address_line_1) ? $job->customer->address_line_1 : '') }}" name="customer_address_line_1" class="w-full address_line_1 h-[35px] rounded-[3px]" type="text" placeholder="Address Line 1" />
                                <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Address Line 2</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->address_line_2) ? $job->customer->address_line_2 : '') }}" name="customer_address_line_2" class="w-full address_line_2 h-[35px] rounded-[3px]" type="text" placeholder="Address Line 2 (Optional)" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Town/City</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->city) ? $job->customer->city : '') }}" name="customer_city" class="w-full city h-[35px] rounded-[3px]" type="text" placeholder="Town/City" />
                                <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Region/County</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->state) ? $job->customer->state : '') }}" name="customer_state" class="w-full state h-[35px] rounded-[3px]" type="text" placeholder="Region/County" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Post Code</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->postal_code) ? $job->customer->postal_code : '') }}" name="customer_postal_code" class="w-full postal_code h-[35px] rounded-[3px]" type="text" placeholder="Post Code" />
                                <div class="acc__input-error error-postal_code text-danger text-xs mt-1"></div>
                            </div>
                            
                            <x-base.form-input name="customer_country" id="country" class="w-full country" type="hidden" value="{{ (isset($job->customer->country) ? $job->customer->country : '') }}" />
                            <x-base.form-input name="customer_latitude" class="w-full latitude" type="hidden" value="{{ (isset($job->customer->latitude) ? $job->customer->latitude : '') }}" />
                            <x-base.form-input name="customer_longitude" class="w-full longitude" type="hidden" value="{{ (isset($job->customer->longitude) ? $job->customer->longitude : '') }}" />
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="0" class="form-wizard-previous-btn" variant="secondary">
                                <x-base.lucide class="h-5 w-5 mr-2" icon="move-left"/>Previous
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" class="form-wizard-next-btn ml-auto" variant="linkedin" >
                                Save & Continue<x-base.lucide class="theIcon h-5 w-5 ml-2" icon="move-right"/>
                            </x-base.button>
                        </div>
                    </form>
                </fieldset>
                <fieldset data-title="Appliance" id="step_3" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium inline-flex items-center">
                            <x-base.lucide style="display: {{ (isset($gbscca1->id) && $gbscca1->id > 0 ? 'none' : 'block') }};" class="w-4 h-4 mr-2 text-danger unsavedIcon" icon="x-circle"/>    
                            <x-base.lucide style="display: {{ (isset($gbscca1->id) && $gbscca1->id > 0 ? 'block' : 'none') }};" class="w-4 h-4 mr-2 text-success savedIcon" icon="check-circle"/> 
                            <span class="info">
                                @if(isset($gbscca1->make->name) && !empty($gbscca1->make->name))
                                    {{ (isset($gbscca1->make->name) && !empty($gbscca1->make->name) ? $gbscca1->make->name.' ' : '') }}
                                @else
                                    Appliance
                                @endif
                            </span>
                        </h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="applianceDetailsForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <input type="hidden" name="appliance_serial" value="1"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Make</x-base.form-label>
                                <x-base.tom-select class="w-full applianceMake" name="app[1][boiler_brand_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($boilers->count() > 0)
                                        @foreach($boilers as $option)
                                            <option {{ (isset($gbscca1->boiler_brand_id) && $gbscca1->boiler_brand_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Model</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gbscca1->model) ? $gbscca1->model : '') }}" name="app[1][model]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Model" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Serial Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gbscca1->serial_no) ? $gbscca1->serial_no : '') }}" name="app[1][serial_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Ttime and temperature control to heating</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[1][appliance_time_temperature_heating_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($timerTemp->count() > 0)
                                        @foreach($timerTemp as $option)
                                            <option {{ (isset($gbscca1->appliance_time_temperature_heating_id) && $gbscca1->appliance_time_temperature_heating_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Time and temperature control to hot water</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tatcthw_yes" name="app[1][tmp_control_hot_water]" {{ (isset($gbscca1->tmp_control_hot_water) && $gbscca1->tmp_control_hot_water != 'Combination boiler' && !empty($gbscca1->tmp_control_hot_water) ? 'Checked' : '') }} value="Cylinder thermostat and programmer/timers" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tatcthw_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px] items-start" style="text-align: left;" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked mt-1" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked mt-1" icon="x-circle"/>
                                            Cylinder thermostat and programmer/timer
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tatcthw_no" name="app[1][tmp_control_hot_water]" {{ (isset($gbscca1->tmp_control_hot_water) && $gbscca1->tmp_control_hot_water == 'Combination boiler' ? 'Checked' : '') }} value="Combination boiler" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tatcthw_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px] items-start" style="text-align: left;" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked mt-1" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked mt-1" icon="x-circle"/>
                                            Combination boiler
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Heating zone valves</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hzv_yes" name="app[1][heating_zone_vlv]" {{ (isset($gbscca1->heating_zone_vlv) && $gbscca1->heating_zone_vlv == 'Fitted' ? 'Checked' : '') }} value="Fitted" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hzv_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Fitted
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hzv_no" name="app[1][heating_zone_vlv]" {{ (isset($gbscca1->heating_zone_vlv) && $gbscca1->heating_zone_vlv == 'Not Required' ? 'Checked' : '') }} value="Not Required" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hzv_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Not Required
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Hot water zone valves</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hwzv_yes" name="app[1][hot_water_zone_vlv]" {{ (isset($gbscca1->hot_water_zone_vlv) && $gbscca1->hot_water_zone_vlv == 'Fitted' ? 'Checked' : '') }} value="Fitted" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hwzv_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Fitted
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hwzv_no" name="app[1][hot_water_zone_vlv]" {{ (isset($gbscca1->hot_water_zone_vlv) && $gbscca1->hot_water_zone_vlv == 'Not Required' ? 'Checked' : '') }} value="Not Required" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hwzv_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Not Required
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Thermostic radiator valves</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tmrvlv_yes" name="app[1][therm_radiator_vlv]" {{ (isset($gbscca1->therm_radiator_vlv) && $gbscca1->therm_radiator_vlv == 'Fitted' ? 'Checked' : '') }} value="Fitted" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tmrvlv_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Fitted
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tmrvlv_no" name="app[1][therm_radiator_vlv]" {{ (isset($gbscca1->therm_radiator_vlv) && $gbscca1->therm_radiator_vlv == 'Not Required' ? 'Checked' : '') }} value="Not Required" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tmrvlv_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Not Required
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Automatic bypass to system</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_autbts_yes" name="app[1][bypass_to_system]" {{ (isset($gbscca1->bypass_to_system) && $gbscca1->bypass_to_system == 'Fitted' ? 'Checked' : '') }} value="Fitted" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_autbts_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Fitted
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_autbts_no" name="app[1][bypass_to_system]" {{ (isset($gbscca1->bypass_to_system) && $gbscca1->bypass_to_system == 'Not Required' ? 'Checked' : '') }} value="Not Required" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_autbts_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Not Required
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Boiler interlock</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_blinloc_yes" name="app[1][boiler_interlock]" {{ (isset($gbscca1->boiler_interlock) && $gbscca1->boiler_interlock == 'Provided' ? 'Checked' : '') }} value="Provided" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_blinloc_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Provided
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_blinloc_no" name="app[1][boiler_interlock]" {{ (isset($gbscca1->boiler_interlock) && $gbscca1->boiler_interlock == 'Not Provided' ? 'Checked' : '') }} value="Not Provided" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_blinloc_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Not Provided
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">All System</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>The system has been flushed and cleaned in accordance with BS7593 and boiler manufacturer's instructions</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_flscln_yes" name="app[1][flushed_and_cleaned]" {{ (isset($gbscca1->flushed_and_cleaned) && $gbscca1->flushed_and_cleaned == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_flscln_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_flscln_no" name="app[1][flushed_and_cleaned]" {{ (isset($gbscca1->flushed_and_cleaned) && $gbscca1->flushed_and_cleaned == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_flscln_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>What cleaner was used?</x-base.form-label>
                                <x-base.form-input name="app[1][clearner_name]" value="{{ (isset($gbscca1->clearner_name) ? $gbscca1->clearner_name : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Clearner Name" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>What inhibitor was used?</x-base.form-label>
                                <div class="block">
                                    <x-base.form-input name="app[1][inhibitor_quantity]" value="{{ (isset($gbscca1->inhibitor_quantity) ? $gbscca1->inhibitor_quantity : '') }}" class="w-full mb-0 h-[35px] rounded-[3px] rounded-bl-none rounded-br-none" type="text" placeholder="Quantity" />
                                    <x-base.form-input name="app[1][inhibitor_amount]" value="{{ (isset($gbscca1->inhibitor_amount) ? $gbscca1->inhibitor_amount : '') }}" class="w-full h-[35px] mt-0 rounded-[3px] rounded-tl-none rounded-tr-none" type="text" placeholder="Liters" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Has a primary water system filter been installed</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hpwsfbi_yes" name="app[1][primary_ws_filter_installed]" {{ (isset($gbscca1->primary_ws_filter_installed) && $gbscca1->primary_ws_filter_installed == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hpwsfbi_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hpwsfbi_no" name="app[1][primary_ws_filter_installed]" {{ (isset($gbscca1->primary_ws_filter_installed) && $gbscca1->primary_ws_filter_installed == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hpwsfbi_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Central Hot Water Mode</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Gas Rate</x-base.form-label>
                                <x-base.form-input name="app[1][gas_rate]" value="{{ (isset($gbscca1->gas_rate) ? $gbscca1->gas_rate : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Ratio" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Gas rate unit</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_gsrunt_yes" name="app[1][gas_rate_unit]" {{ (isset($gbscca1->gas_rate_unit) && $gbscca1->gas_rate_unit == 'm3/hr' ? 'Checked' : '') }} value="m3/hr" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_gsrunt_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            m<sup>3</sup>/hr
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_gsrunt_no" name="app[1][gas_rate_unit]" {{ (isset($gbscca1->gas_rate_unit) && $gbscca1->gas_rate_unit == 'ft3/hr' ? 'Checked' : '') }} value="ft3/hr" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_gsrunt_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            ft<sup>3</sup>/hr
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Central heating output left at factory setting</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_cholafs_yes" name="app[1][cho_factory_setting]" {{ (isset($gbscca1->cho_factory_setting) && $gbscca1->cho_factory_setting == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_cholafs_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_cholafs_no" name="app[1][cho_factory_setting]" {{ (isset($gbscca1->cho_factory_setting) && $gbscca1->cho_factory_setting == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_cholafs_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_cholafs_na" name="app[1][cho_factory_setting]" {{ (isset($gbscca1->cho_factory_setting) && $gbscca1->cho_factory_setting == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_cholafs_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Burner operating pressure (or intel pressure)</x-base.form-label>
                                <x-base.form-input name="app[1][burner_opt_pressure]" value="{{ (isset($gbscca1->burner_opt_pressure) ? $gbscca1->burner_opt_pressure : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Buner pressure" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Burner operating pressure (or intel pressure) Unit</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_bopoipunt_yes" name="app[1][burner_opt_pressure_unit]" {{ (isset($gbscca1->burner_opt_pressure_unit) && $gbscca1->burner_opt_pressure_unit == 'mbar' ? 'Checked' : '') }} value="mbar" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_bopoipunt_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            mbar
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_bopoipunt_no" name="app[1][burner_opt_pressure_unit]" {{ (isset($gbscca1->burner_opt_pressure_unit) && $gbscca1->burner_opt_pressure_unit == 'kW/h' ? 'Checked' : '') }} value="kW/h" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_bopoipunt_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            kW/h
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_bopoipunt_na" name="app[1][burner_opt_pressure_unit]" {{ (isset($gbscca1->burner_opt_pressure_unit) && $gbscca1->burner_opt_pressure_unit == 'Btu/h' ? 'Checked' : '') }} value="Btu/h" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_bopoipunt_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Btu/h
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Central heating flow temperature</x-base.form-label>
                                <x-base.form-input name="app[1][centeral_heat_flow_temp]" value="{{ (isset($gbscca1->centeral_heat_flow_temp) ? $gbscca1->centeral_heat_flow_temp : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Central heating return temperature</x-base.form-label>
                                <x-base.form-input name="app[1][centeral_heat_return_temp]" value="{{ (isset($gbscca1->centeral_heat_return_temp) ? $gbscca1->centeral_heat_return_temp : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Combination boilers only</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Is the installation in a hard water area (above 200ppm)</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_itiiahwara2_yes" name="app[1][is_in_hard_water_area]" {{ (isset($gbscca1->is_in_hard_water_area) && $gbscca1->is_in_hard_water_area == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_itiiahwara2_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_itiiahwara2_no" name="app[1][is_in_hard_water_area]" {{ (isset($gbscca1->is_in_hard_water_area) && $gbscca1->is_in_hard_water_area == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_itiiahwara2_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>If yes and if required by the manufacturer, has the water scale reducer been fitted?</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_iyairmsf_yes" name="app[1][is_scale_reducer_fitted]" {{ (isset($gbscca1->is_scale_reducer_fitted) && $gbscca1->is_scale_reducer_fitted == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_iyairmsf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_iyairmsf_no" name="app[1][is_scale_reducer_fitted]" {{ (isset($gbscca1->is_scale_reducer_fitted) && $gbscca1->is_scale_reducer_fitted == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_iyairmsf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>What type of scale reducer been fitted?</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_wtosrbf_yes" name="app[1][what_reducer_fitted]" {{ (isset($gbscca1->what_reducer_fitted) && $gbscca1->what_reducer_fitted == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_wtosrbf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_wtosrbf_no" name="app[1][what_reducer_fitted]" {{ (isset($gbscca1->what_reducer_fitted) && $gbscca1->what_reducer_fitted == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_wtosrbf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Domestic hot water mode</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Gas Rate</x-base.form-label>
                                <x-base.form-input name="app[1][dom_gas_rate]" value="{{ (isset($gbscca1->dom_gas_rate) ? $gbscca1->dom_gas_rate : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Ratio" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Gas rate unit</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_dgsrunt_yes" name="app[1][dom_gas_rate_unit]" {{ (isset($gbscca1->dom_gas_rate_unit) && $gbscca1->dom_gas_rate_unit == 'm3/hr' ? 'Checked' : '') }} value="m3/hr" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_dgsrunt_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            m<sup>3</sup>/hr
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_dgsrunt_no" name="app[1][dom_gas_rate_unit]" {{ (isset($gbscca1->dom_gas_rate_unit) && $gbscca1->dom_gas_rate_unit == 'ft3/hr' ? 'Checked' : '') }} value="ft3/hr" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_dgsrunt_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            ft<sup>3</sup>/hr
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Burner operating pressure (or intel pressure)</x-base.form-label>
                                <x-base.form-input name="app[1][dom_burner_opt_pressure]" value="{{ (isset($gbscca1->dom_burner_opt_pressure) ? $gbscca1->dom_burner_opt_pressure : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Buner pressure" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Burner operating pressure (or intel pressure) Unit</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_dbopoipunt_yes" name="app[1][dom_burner_opt_pressure_unit]" {{ (isset($gbscca1->dom_burner_opt_pressure_unit) && $gbscca1->dom_burner_opt_pressure_unit == 'mbar' ? 'Checked' : '') }} value="mbar" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_dbopoipunt_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            mbar
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_dbopoipunt_no" name="app[1][dom_burner_opt_pressure_unit]" {{ (isset($gbscca1->dom_burner_opt_pressure_unit) && $gbscca1->dom_burner_opt_pressure_unit == 'kW/h' ? 'Checked' : '') }} value="kW/h" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_dbopoipunt_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            kW/h
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_dbopoipunt_na" name="app[1][dom_burner_opt_pressure_unit]" {{ (isset($gbscca1->dom_burner_opt_pressure_unit) && $gbscca1->dom_burner_opt_pressure_unit == 'Btu/h' ? 'Checked' : '') }} value="Btu/h" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_dbopoipunt_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Btu/h
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Cold water intel temperature</x-base.form-label>
                                <x-base.form-input name="app[1][dom_cold_water_temp]" value="{{ (isset($gbscca1->dom_cold_water_temp) ? $gbscca1->dom_cold_water_temp : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Hot water has been checked at all outlet</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_ditiiahwara2_yes" name="app[1][dom_checked_outlet]" {{ (isset($gbscca1->dom_checked_outlet) && $gbscca1->dom_checked_outlet == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_ditiiahwara2_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_ditiiahwara2_no" name="app[1][dom_checked_outlet]" {{ (isset($gbscca1->dom_checked_outlet) && $gbscca1->dom_checked_outlet == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_ditiiahwara2_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Water flow rate</x-base.form-label>
                                <x-base.form-input name="app[1][dom_water_flow_rate]" value="{{ (isset($gbscca1->dom_water_flow_rate) ? $gbscca1->dom_water_flow_rate : '') }}" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Condensing Boilers Only</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>The condensate drain has been installed in accordance with the manufacturer's instructions and/or BS5546/BS6798</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tcdhbiam_yes" name="app[1][con_drain_installed]" {{ (isset($gbscca1->con_drain_installed) && $gbscca1->con_drain_installed == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tcdhbiam_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tcdhbiam_no" name="app[1][con_drain_installed]" {{ (isset($gbscca1->con_drain_installed) && $gbscca1->con_drain_installed == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tcdhbiam_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Pint of termination</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_potrm_yes" name="app[1][point_of_termination]" {{ (isset($gbscca1->point_of_termination) && $gbscca1->point_of_termination == 'Internal' ? 'Checked' : '') }} value="Internal" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_potrm_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Internal
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_potrm_no" name="app[1][point_of_termination]" {{ (isset($gbscca1->point_of_termination) && $gbscca1->point_of_termination == 'External' ? 'Checked' : '') }} value="External" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_potrm_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            External
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_potrm_na" name="app[1][point_of_termination]" {{ (isset($gbscca1->point_of_termination) && $gbscca1->point_of_termination == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_potrm_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Method of disposal</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_modip_yes" name="app[1][dispsal_method]" {{ (isset($gbscca1->dispsal_method) && $gbscca1->dispsal_method == 'Gravity' ? 'Checked' : '') }} value="Gravity" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_modip_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Gravity
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_modip_no" name="app[1][dispsal_method]" {{ (isset($gbscca1->dispsal_method) && $gbscca1->dispsal_method == 'Pumped' ? 'Checked' : '') }} value="Pumped" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_modip_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Pumped
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_modip_na" name="app[1][dispsal_method]" {{ (isset($gbscca1->dispsal_method) && $gbscca1->dispsal_method == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_modip_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">All Installations</h2>
                            </div>
                            <div class="col-span-12 pt-2">
                                <x-base.form-label>Min Readings</x-base.form-label>
                                <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gbscca1->min_ratio) ? $gbscca1->min_ratio : '') }}" name="app[1][min_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">CO (PPM)</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gbscca1->min_co) ? $gbscca1->min_co : '') }}" name="app[1][min_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">CO<sub>2</sub> (%)</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gbscca1->min_co2) ? $gbscca1->min_co2 : '') }}" name="app[1][min_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 pt-2">
                                <x-base.form-label>Max Readings</x-base.form-label>
                                <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gbscca1->max_ratio) ? $gbscca1->max_ratio : '') }}" name="app[1][max_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">CO (PPM)</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gbscca1->max_co) ? $gbscca1->max_co : '') }}" name="app[1][max_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">CO<sub>2</sub> (%)</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gbscca1->max_co2) ? $gbscca1->max_co2 : '') }}" name="app[1][max_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>The heating and hot water system complies with the appropriate Building Regulations</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_apprbuilreg_yes" name="app[1][app_building_regulation]" {{ (isset($gbscca1->app_building_regulation) && $gbscca1->app_building_regulation == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_apprbuilreg_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_apprbuilreg_no" name="app[1][app_building_regulation]" {{ (isset($gbscca1->app_building_regulation) && $gbscca1->app_building_regulation == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_apprbuilreg_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>The boiler and associated products have been installed and commissioned in accordance with the manufacturer's instructions</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_baicami_yes" name="app[1][commissioned_man_ins]" {{ (isset($gbscca1->commissioned_man_ins) && $gbscca1->commissioned_man_ins == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_baicami_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_baicami_no" name="app[1][commissioned_man_ins]" {{ (isset($gbscca1->commissioned_man_ins) && $gbscca1->commissioned_man_ins == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_baicami_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>The operation of the boiler system controls have been demonstrated to and understood by the customer</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_bscduc_yes" name="app[1][demonstrated_understood]" {{ (isset($gbscca1->demonstrated_understood) && $gbscca1->demonstrated_understood == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_bscduc_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_bscduc_no" name="app[1][demonstrated_understood]" {{ (isset($gbscca1->demonstrated_understood) && $gbscca1->demonstrated_understood == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_bscduc_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>The manufacturer's literature, including Benchmark Checklist and Service Record, has been explained and left with the customer</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_mlibcsrex_yes" name="app[1][literature_including]" {{ (isset($gbscca1->literature_including) && $gbscca1->literature_including == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_mlibcsrex_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_mlibcsrex_no" name="app[1][literature_including]" {{ (isset($gbscca1->literature_including) && $gbscca1->literature_including == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_mlibcsrex_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Next Inspection</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Does the next inspection apply to this certificate?</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_dtniatc_yes" name="app[1][is_next_inspection]" {{ (isset($gbscca1->is_next_inspection) && $gbscca1->is_next_inspection == 'Applicable' ? 'Checked' : '') }} value="Applicable" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_dtniatc_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Applicable
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_dtniatc_no" name="app[1][is_next_inspection]" {{ (isset($gbscca1->is_next_inspection) && $gbscca1->is_next_inspection == 'Not Applicable' ? 'Checked' : '') }} value="Not Applicable" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_dtniatc_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Not Applicable
                                        </x-base.button>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-500 mt-2">
                                    If "Not Applicable" selected for this option, this inspection date will not be displayed on this certificate and
                                    the reminder for this certificate will not be scheduled.
                                 </div>
                            </div>
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="1" class="form-wizard-previous-btn" variant="secondary">
                                <x-base.lucide class="h-5 w-5 mr-2" icon="move-left"/>Previous
                            </x-base.button>
                            <x-base.button type="button" data-appliance="1" class="form-wizard-next-btn ml-auto" variant="linkedin" >
                                Save & Continue<x-base.lucide class="theIcon h-5 w-5 ml-2" icon="move-right"/>
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        </div>
                    </form>
                </fieldset>
                <fieldset id="step_4" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium">Signature</h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="signatureForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            @php 
                                $inspectionDeate = (isset($gbscc->inspection_date) && !empty($gbscc->inspection_date) ? date('d-m-Y', strtotime($gbscc->inspection_date)) : date('d-m-Y'));
                                $nextInspectionDate = (isset($gbscc->next_inspection_date) && !empty($gbscc->next_inspection_date) ? date('d-m-Y', strtotime($gbscc->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                            @endphp
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Today's Date</x-base.form-label>
                                <x-base.litepicker name="inspection_date" id="date_issued" value="{{ $inspectionDeate }}" class="w-full h-[35px] rounded-[3px]" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Next Inspection Date</x-base.form-label>
                                <x-base.litepicker value="{{ $nextInspectionDate }}" name="next_inspection_date" class="w-full h-[35px] rounded-[3px]"  data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Received By</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gbscc->received_by) && !empty($gbscc->received_by) ? $gbscc->received_by : '') }}" type="text" name="received_by" class="w-full h-[35px] rounded-[3px]" placeholder=""/>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Relation</x-base.form-label>
                                <x-base.tom-select class="w-full" name="relation_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($relations->count() > 0)
                                        @foreach($relations as $option)
                                            <option {{ (isset($gbscc->relation_id) && $gbscc->relation_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            @if($signature)
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-0 block w-full">Signature</x-base.form-label>
                                <img src="{{ $signature }}" alt="signature" class="h-[60px] w-auto inline-block"/>
                            </div>
                            @endif
                            <div class="col-span-12 sm:col-span-12 pt-2">
                                <div class="gsfSignature border rounded-[3px] h-auto py-0 sm:py-10 bg-slate-100 rounded-b-none flex justify-center items-center">
                                    <x-creagia-signature-pad name='sign'
                                        border-color="#e5e7eb"
                                        submit-name="Save"
                                        clear-name="Clear Signature"
                                        submit-id="signSaveBtn"
                                        clear-id="clear"
                                        pad-classes="w-auto h-48 bg-white mt-0"
                                        width="600" 
                                        height="300"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="0" class="form-wizard-previous-btn" variant="secondary">
                                <x-base.lucide class="h-5 w-5 mr-2" icon="move-left"/>Previous
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" class="form-wizard-final-btn ml-auto" variant="linkedin" >
                                Save & Continue<x-base.lucide class="theIcon h-5 w-5 ml-2" icon="move-right"/>
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        </div>
                    </form>
                </fieldset>
            </div>
        </div>
    </div>
    

    @include('app.records.gas_warning_notice.modals')
    @include('app.action-modals')
@endsection
@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/custom/signature.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/records/gas_boiler_system_commissioning_checklist.js')
@endPushOnce