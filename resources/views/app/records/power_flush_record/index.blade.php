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
                            <x-base.button type="button" data-appliance="0" data-id="step_3" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gpfrc->id) && $gpfrc->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gpfrc->id) && $gpfrc->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>  
                                Powerflush Checklist <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_4" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gpfrr) && $gpfrr->count() > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gpfrr) && $gpfrr->count() > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>  
                                Radiators <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_5" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gpfr->has_signatures) && $gpfr->has_signatures ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gpfr->has_signatures) && $gpfr->has_signatures ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>  
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
                <fieldset id="step_3" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium inline-flex items-center">
                            <x-base.lucide class="w-4 h-4 text-success mr-2" icon="check-circle"/>
                            Powerflush Checklist
                        </h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="powerFlushChecklistForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type of System</x-base.form-label>
                                <x-base.tom-select class="w-full" name="powerflush_system_type_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($flush_types->count() > 0)
                                        @foreach($flush_types as $option)
                                            <option {{ (isset($gpfrc->powerflush_system_type_id) && $gpfrc->powerflush_system_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Age of System</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Boiler</x-base.form-label>
                                <x-base.tom-select class="w-full" name="boiler_brand_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($boilers->count() > 0)
                                        @foreach($boilers as $option)
                                            <option {{ (isset($gpfrc->boiler_brand_id) && $gpfrc->boiler_brand_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Radiators</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gpfrc->radiators) ? $gpfrc->radiators : '') }}" name="radiators" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Pipework</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gpfrc->pipework) ? $gpfrc->pipework : '') }}" name="pipework" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type of boiler</x-base.form-label>
                                <x-base.tom-select class="w-full" name="appliance_type_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($types->count() > 0)
                                        @foreach($types as $option)
                                            <option {{ (isset($gpfrc->appliance_type_id) && $gpfrc->appliance_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Location of Boiler</x-base.form-label>
                                <x-base.tom-select class="w-full" name="appliance_location_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($locations->count() > 0)
                                        @foreach($locations as $option)
                                            <option {{ (isset($gpfrc->appliance_location_id) && $gpfrc->appliance_location_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Serial Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gpfrc->serial_no) ? $gpfrc->serial_no : '') }}" name="serial_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type of Water Cylinder</x-base.form-label>
                                <x-base.tom-select class="w-full" name="powerflush_cylinder_type_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($flush_cylinder->count() > 0)
                                        @foreach($flush_cylinder as $option)
                                            <option {{ (isset($gpfrc->powerflush_cylinder_type_id) && $gpfrc->powerflush_cylinder_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type of Pipework</x-base.form-label>
                                <x-base.tom-select class="w-full" name="powerflush_pipework_type_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($flush_pipework->count() > 0)
                                        @foreach($flush_pipework as $option)
                                            <option {{ (isset($gpfrc->powerflush_pipework_type_id) && $gpfrc->powerflush_pipework_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">If microbore system</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Are twin entry radiator valves fitted</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_atervf_yes" name="twin_radiator_vlv_fitted" {{ (isset($gpfrc->twin_radiator_vlv_fitted) && $gpfrc->twin_radiator_vlv_fitted == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_atervf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_atervf_no" name="twin_radiator_vlv_fitted" {{ (isset($gpfrc->twin_radiator_vlv_fitted) && $gpfrc->twin_radiator_vlv_fitted == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_atervf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>If so, are all radiators completely warm when boiler fired</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_isaarcwwbf_yes" name="completely_warm_on_fired" {{ (isset($gpfrc->completely_warm_on_fired) && $gpfrc->completely_warm_on_fired == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_isaarcwwbf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_isaarcwwbf_no" name="completely_warm_on_fired" {{ (isset($gpfrc->completely_warm_on_fired) && $gpfrc->completely_warm_on_fired == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_isaarcwwbf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">If single pipe system</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Is there circulation (heat) to all radiators</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_itchtar_yes" name="circulation_for_all_readiators" {{ (isset($gpfrc->circulation_for_all_readiators) && $gpfrc->circulation_for_all_readiators == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_itchtar_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_itchtar_no" name="circulation_for_all_readiators" {{ (isset($gpfrc->circulation_for_all_readiators) && $gpfrc->circulation_for_all_readiators == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_itchtar_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">If elderly steel pipework</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Is system sufficiently sound to power flush</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_issstpf_yes" name="suffifiently_sound" {{ (isset($gpfrc->suffifiently_sound) && $gpfrc->suffifiently_sound == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_issstpf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_issstpf_no" name="suffifiently_sound" {{ (isset($gpfrc->suffifiently_sound) && $gpfrc->suffifiently_sound == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_issstpf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Location of system circulator pump</x-base.form-label>
                                <x-base.tom-select class="w-full" name="powerflush_circulator_pump_location_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($flush_pump_location->count() > 0)
                                        @foreach($flush_pump_location as $option)
                                            <option {{ (isset($gpfrc->powerflush_circulator_pump_location_id) && $gpfrc->powerflush_circulator_pump_location_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Number of radiators</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gpfrc->number_of_radiators) ? $gpfrc->number_of_radiators : '') }}" name="number_of_radiators" class="w-full h-[35px] rounded-[3px]" type="number" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Radiator Type</x-base.form-label>
                                <x-base.tom-select class="w-full" name="radiator_type_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($radiator_type->count() > 0)
                                        @foreach($radiator_type as $option)
                                            <option {{ (isset($gpfrc->radiator_type_id) && $gpfrc->radiator_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Are they getting warm</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_atgw_yes" name="getting_warm" {{ (isset($gpfrc->getting_warm) && $gpfrc->getting_warm == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_atgw_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_atgw_no" name="getting_warm" {{ (isset($gpfrc->getting_warm) && $gpfrc->getting_warm == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_atgw_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Are TRV's fitted</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_atrvf_yes" name="are_trvs_fitted" {{ (isset($gpfrc->are_trvs_fitted) && $gpfrc->are_trvs_fitted == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_atrvf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_atrvf_no" name="are_trvs_fitted" {{ (isset($gpfrc->are_trvs_fitted) && $gpfrc->are_trvs_fitted == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_atrvf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Any obvious signs of neglect/leak</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_aosonl_yes" name="sign_of_neglect" {{ (isset($gpfrc->sign_of_neglect) && $gpfrc->sign_of_neglect == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_aosonl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_aosonl_no" name="sign_of_neglect" {{ (isset($gpfrc->sign_of_neglect) && $gpfrc->sign_of_neglect == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_aosonl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Do all thermostic radiator valves (TRV's) open fully</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_datrvof_yes" name="radiator_open_fully" {{ (isset($gpfrc->radiator_open_fully) && $gpfrc->radiator_open_fully == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_datrvof_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_datrvof_no" name="radiator_open_fully" {{ (isset($gpfrc->radiator_open_fully) && $gpfrc->radiator_open_fully == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_datrvof_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">All there zone valves / Where are they located</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Number of valves</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gpfrc->number_of_valves) ? $gpfrc->number_of_valves : '') }}" name="number_of_valves" class="w-full h-[35px] rounded-[3px]" type="number" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Location</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_atzvl_yes" name="valves_located" {{ (isset($gpfrc->valves_located) && $gpfrc->valves_located == 'Airing Cupboard' ? 'Checked' : '') }} value="Airing Cupboard" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_atzvl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Airing Cupboard
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_atzvl_no" name="valves_located" {{ (isset($gpfrc->valves_located) && $gpfrc->valves_located == 'Elsewhere' ? 'Checked' : '') }} value="Elsewhere" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_atzvl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Elsewhere
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">F & E Tank</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Location</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gpfrc->fe_tank_location) ? $gpfrc->fe_tank_location : '') }}" name="fe_tank_location" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Checked</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_fetnkck_yes" name="fe_tank_checked" {{ (isset($gpfrc->fe_tank_checked) && $gpfrc->fe_tank_checked == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_fetnkck_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_fetnkck_no" name="fe_tank_checked" {{ (isset($gpfrc->fe_tank_checked) && $gpfrc->fe_tank_checked == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_fetnkck_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Condition</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gpfrc->fe_tank_condition) ? $gpfrc->fe_tank_condition : '') }}" name="fe_tank_condition" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Color of heating system water, as run from bottom of radiator</x-base.form-label>
                                <x-base.tom-select class="w-full" name="color_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($color->count() > 0)
                                        @foreach($color as $option)
                                            <option {{ (isset($gpfrc->color_id) && $gpfrc->color_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Visual inspection of system water before PowerFlush</x-base.form-label>
                                <x-base.tom-select class="w-full" name="before_color_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($color->count() > 0)
                                        @foreach($color as $option)
                                            <option {{ (isset($gpfrc->before_color_id) && $gpfrc->before_color_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12">
                                <x-base.table bordered sm id="invoiceItemsTable">
                                    <x-base.table.thead class="max-sm:hidden">
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-normal text-left">Test Parameter</x-base.table.th>
                                            <x-base.table.th class="whitespace-normal text-left">pH</x-base.table.th>
                                            <x-base.table.th class="whitespace-normal text-left">chloride (ppm)</x-base.table.th>
                                            <x-base.table.th class="whitespace-normal text-left">Hardness</x-base.table.th>
                                            <x-base.table.th class="whitespace-normal text-left">Inhibitor (ppm molybdate)</x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-normal text-left">Mains water</x-base.table.th>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->mw_ph) ? $gpfrc->mw_ph : '') }}" name="mw_ph" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->mw_chloride) ? $gpfrc->mw_chloride : '') }}" name="mw_chloride" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->mw_hardness) ? $gpfrc->mw_hardness : '') }}" name="mw_hardness" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->mw_inhibitor) ? $gpfrc->mw_inhibitor : '') }}" name="mw_inhibitor" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-normal text-left">System water before PowerFlush</x-base.table.th>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->bpf_ph) ? $gpfrc->bpf_ph : '') }}" name="bpf_ph" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->bpf_chloride) ? $gpfrc->bpf_chloride : '') }}" name="bpf_chloride" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->bpf_hardness) ? $gpfrc->bpf_hardness : '') }}" name="bpf_hardness" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->bpf_inhibitor) ? $gpfrc->bpf_inhibitor : '') }}" name="bpf_inhibitor" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-normal text-left">System water after PowerFlush</x-base.table.th>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->apf_ph) ? $gpfrc->apf_ph : '') }}" name="apf_ph" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->apf_chloride) ? $gpfrc->apf_chloride : '') }}" name="apf_chloride" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->apf_hardness) ? $gpfrc->apf_hardness : '') }}" name="apf_hardness" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                            <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->apf_inhibitor) ? $gpfrc->apf_inhibitor : '') }}" name="apf_inhibitor" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">TDS Readings</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Mains Water</x-base.form-label>
                                <x-base.input-group class="mt-0" inputGroup >
                                    <x-base.form-input value="{{ (isset($gpfrc->mw_tds_reading) ? $gpfrc->mw_tds_reading : '') }}" name="mw_tds_reading" class="w-full rounded-[3px]" type="text" placeholder="" />
                                    <x-base.input-group.text>ppm</x-base.input-group.text>
                                </x-base.input-group>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>System water before flush</x-base.form-label>
                                <x-base.input-group class="mt-0" inputGroup >
                                    <x-base.form-input value="{{ (isset($gpfrc->bf_tds_reading) ? $gpfrc->bf_tds_reading : '') }}" name="bf_tds_reading" class="w-full rounded-[3px]" type="text" placeholder="" />
                                    <x-base.input-group.text>ppm</x-base.input-group.text>
                                </x-base.input-group>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>System water after flush</x-base.form-label>
                                <x-base.input-group class="mt-0" inputGroup >
                                    <x-base.form-input value="{{ (isset($gpfrc->af_tds_reading) ? $gpfrc->af_tds_reading : '') }}" name="af_tds_reading" class="w-full rounded-[3px]" type="text" placeholder="" />
                                    <x-base.input-group.text>ppm</x-base.input-group.text>
                                </x-base.input-group>
                            </div>
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
                <fieldset id="step_4" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium inline-flex items-center">
                            <x-base.lucide class="w-4 h-4 text-success mr-2" icon="check-circle"/>
                            Radiators
                        </h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="radiatorsForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12">
                                <div class="gasappRadiatorsWrap">
                                    <div class="gasappRadiatorsAccordion" style="display: {{ isset($gpfrr) && $gpfrr->count() > 0 ? 'block' : 'none' }};">
                                        <div id="gasapp-accordion-radiators" class="gasapp-accordion border-none">
                                            @if(isset($gpfrr) && $gpfrr->count() > 0)
                                                @php $sl = 1; @endphp
                                                @foreach($gpfrr as $item)
                                                    <div class="gasapp-accordion-item mb-2" data-serial="{{ $sl }}">
                                                        <div id="gasapp-accr-radiators-content-{{ $sl }}" class="gasapp-accordion-header relative">
                                                            <button class="gasapp-accordion-button relative bg-primary text-white text-[14px] capitalize w-full text-left font-medium px-5 py-4 [&.gasapp-collapsed]:bg-slate-200 [&.gasapp-collapsed]:text-primary gasapp-collapsed" type="button">
                                                                <span class="radiatorTitle">({{ $sl }}) Rediator {{ (isset($item->rediator_location) && !empty($item->rediator_location) ? '('.$item->rediator_location.')' : '') }}</span>
                                                                <span class="accordionCollaps"></span>
                                                            </button>
                                                            <button data-id="{{ $item->id }}" type="button" style="right: 20px;" class="deleteRadiator absolute rounded-full top-0 bottom-0 my-auto bg-danger text-white w-[30px] h-[30px] inline-flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="trash-2" class="lucide lucide-trash-2 stroke-1.5 w-4 h-4 text-white"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg></button>
                                                        </div>
                                                        <div id="gasapp-accr-radiators-collapse-{{ $sl }}" class="gasapp-accordion-collapse" style="display: none;">
                                                            <div class="gasapp-accordion-body border border-slate-200 border-t-0 p-5">
                                                                <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                                                                    <div class="col-span-12">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Rediator Location</label>
                                                                        <input value="{{ (isset($item->rediator_location) && !empty($item->rediator_location) ? $item->rediator_location : '') }}" name="red[{{ $sl }}][rediator_location]" type="text" placeholder="" class="reaiator_location_name disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>
                                                                    <div class="col-span-12">
                                                                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Temperature before powerflus in °C</h2>
                                                                    </div>
                                                                    <div class="col-span-12 sm:col-span-3">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Top</label>
                                                                        <input value="{{ (isset($item->tmp_b_top) && !empty($item->tmp_b_top) ? $item->tmp_b_top : '') }}" name="red[{{ $sl }}][tmp_b_top]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>
                                                                    <div class="col-span-12 sm:col-span-3">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Bottom</label>
                                                                        <input value="{{ (isset($item->tmp_b_bottom) && !empty($item->tmp_b_bottom) ? $item->tmp_b_bottom : '') }}" name="red[{{ $sl }}][tmp_b_bottom]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>
                                                                    <div class="col-span-12 sm:col-span-3">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Left</label>
                                                                        <input value="{{ (isset($item->tmp_b_left) && !empty($item->tmp_b_left) ? $item->tmp_b_left : '') }}" name="red[{{ $sl }}][tmp_b_left]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>
                                                                    <div class="col-span-12 sm:col-span-3">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Right</label>
                                                                        <input value="{{ (isset($item->tmp_b_right) && !empty($item->tmp_b_right) ? $item->tmp_b_right : '') }}" name="red[{{ $sl }}][tmp_b_right]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>

                                                                    <div class="col-span-12">
                                                                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Temperature After powerflus in °C</h2>
                                                                    </div>
                                                                    <div class="col-span-12 sm:col-span-3">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Top</label>
                                                                        <input value="{{ (isset($item->tmp_a_top) && !empty($item->tmp_a_top) ? $item->tmp_a_top : '') }}" name="red[{{ $sl }}][tmp_a_top]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>
                                                                    <div class="col-span-12 sm:col-span-3">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Bottom</label>
                                                                        <input value="{{ (isset($item->tmp_a_bottom) && !empty($item->tmp_a_bottom) ? $item->tmp_a_bottom : '') }}" name="red[{{ $sl }}][tmp_a_bottom]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>
                                                                    <div class="col-span-12 sm:col-span-3">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Left</label>
                                                                        <input value="{{ (isset($item->tmp_a_left) && !empty($item->tmp_a_left) ? $item->tmp_a_left : '') }}" name="red[{{ $sl }}][tmp_a_left]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>
                                                                    <div class="col-span-12 sm:col-span-3">
                                                                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Right</label>
                                                                        <input value="{{ (isset($item->tmp_a_right) && !empty($item->tmp_a_right) ? $item->tmp_a_right : '') }}" name="red[{{ $sl }}][tmp_a_right]" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php $sl++; @endphp
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div role="alert" style="display: {{ isset($gpfrr) && $gpfrr->count() > 0 ? 'none' : 'flex' }};" class="gasappRadiatorsNotice alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center"><i data-tw-merge data-lucide="alert-triangle" class="stroke-1.5 w-5 h-5 mr-2"></i>Right now there are no radiator available.</div>
                                </div>
                            </div>
                            <div class="col-span-12 text-right">
                                <x-base.button type="button" id="addReadiatorBtn" class="addReadiatorBtn" variant="primary">
                                    <x-base.lucide class="h-5 w-5 mr-2" icon="plus-circle"/>Add Radiator
                                </x-base.button>
                            </div>
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
                <fieldset id="step_5" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium">Signature</h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="signatureForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            @php 
                                $inspectionDeate = (isset($gpfr->inspection_date) && !empty($gpfr->inspection_date) ? date('d-m-Y', strtotime($gpfr->inspection_date)) : date('d-m-Y'));
                                $nextInspectionDate = (isset($gpfr->next_inspection_date) && !empty($gpfr->next_inspection_date) ? date('d-m-Y', strtotime($gpfr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
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
                                <x-base.form-input value="{{ (isset($gpfr->received_by) && !empty($gpfr->received_by) ? $gpfr->received_by : '') }}" type="text" name="received_by" class="w-full h-[35px] rounded-[3px]" placeholder=""/>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Relation</x-base.form-label>
                                <x-base.tom-select class="w-full" name="relation_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($relations->count() > 0)
                                        @foreach($relations as $option)
                                            <option {{ (isset($gpfr->relation_id) && $gpfr->relation_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
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
    @vite('resources/js/app/records/power_flush_record.js')
@endPushOnce