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
                            <x-base.button  data-appliance="0" type="button" data-id="step_3" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($guhwcrs->id) && $guhwcrs->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($guhwcrs->id) && $guhwcrs->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/> 
                                <span class="info">
                                    Unvented Hot Water System
                                </span>
                                <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button  data-appliance="0" type="button" data-id="step_4" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($guhwcri->id) && $guhwcri->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($guhwcri->id) && $guhwcri->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/> 
                                <span class="info">
                                    Inspection Record
                                </span>
                                <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_5" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($guhwcr->has_signatures) && $guhwcr->has_signatures ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($guhwcr->has_signatures) && $guhwcr->has_signatures ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>  
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
                            <x-base.lucide style="display: {{ (isset($guhwcrs->id) && $guhwcrs->id > 0 ? 'none' : 'block') }};" class="w-4 h-4 mr-2 text-danger unsavedIcon" icon="x-circle"/>    
                            <x-base.lucide style="display: {{ (isset($guhwcrs->id) && $guhwcrs->id > 0 ? 'block' : 'none') }};" class="w-4 h-4 mr-2 text-success savedIcon" icon="check-circle"/> 
                            <span class="info">
                                Unvented Hot Water System
                            </span>
                        </h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="hotWaterSystemForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->type) && !empty($guhwcrs->type) ? $guhwcrs->type : '') }}" name="type" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Make</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->make) && !empty($guhwcrs->make) ? $guhwcrs->make : '') }}" name="make" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Model</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->model) && !empty($guhwcrs->model) ? $guhwcrs->model : '') }}" name="model" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Location</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->location) && !empty($guhwcrs->location) ? $guhwcrs->location : '') }}" name="location" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Serial Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->serial_no) && !empty($guhwcrs->serial_no) ? $guhwcrs->serial_no : '') }}" name="serial_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>GC Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->gc_number) && !empty($guhwcrs->gc_number) ? $guhwcrs->gc_number : '') }}" name="gc_number" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            
                            
                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Inspection Details</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Indirect or Direct</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_inddir_yes" name="direct_or_indirect" {{ (isset($guhwcrs->direct_or_indirect) && $guhwcrs->direct_or_indirect == 'Indirect' ? 'Checked' : '') }} value="Indirect" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_inddir_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Indirect
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_inddir_no" name="direct_or_indirect" {{ (isset($guhwcrs->direct_or_indirect) && $guhwcrs->direct_or_indirect == 'Direct' ? 'Checked' : '') }} value="Direct" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_inddir_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Direct
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Gas boiler and/or solar, or Immersion heaters</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->boiler_solar_immersion) && !empty($guhwcrs->boiler_solar_immersion) ? $guhwcrs->boiler_solar_immersion : '') }}" name="boiler_solar_immersion" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Capacity (Ltrs)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->capacity) && !empty($guhwcrs->capacity) ? $guhwcrs->capacity : '') }}" name="capacity" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Makers warning labels attached</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_mwlatch_yes" name="warning_label_attached" {{ (isset($guhwcrs->warning_label_attached) && $guhwcrs->warning_label_attached == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_mwlatch_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_mwlatch_no" name="warning_label_attached" {{ (isset($guhwcrs->warning_label_attached) && $guhwcrs->warning_label_attached == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_mwlatch_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_mwlatch_na" name="warning_label_attached" {{ (isset($guhwcrs->warning_label_attached) && $guhwcrs->warning_label_attached == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_mwlatch_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Inlet Water Pressure</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->water_pressure) && !empty($guhwcrs->water_pressure) ? $guhwcrs->water_pressure : '') }}" name="water_pressure" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flow rate</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->flow_rate) && !empty($guhwcrs->flow_rate) ? $guhwcrs->flow_rate : '') }}" name="flow_rate" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Fully commissioned</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcrs->fully_commissioned) && !empty($guhwcrs->fully_commissioned) ? $guhwcrs->fully_commissioned : '') }}" name="fully_commissioned" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="0" class="form-wizard-previous-btn" variant="secondary">
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
                        <h2 class="text-base font-medium inline-flex items-center">
                            <x-base.lucide style="display: {{ (isset($guhwcri->id) && $guhwcri->id > 0 ? 'none' : 'block') }};" class="w-4 h-4 mr-2 text-danger unsavedIcon" icon="x-circle"/>    
                            <x-base.lucide style="display: {{ (isset($guhwcri->id) && $guhwcri->id > 0 ? 'block' : 'none') }};" class="w-4 h-4 mr-2 text-success savedIcon" icon="check-circle"/> 
                            <span class="info">
                                Inspection Record
                            </span>
                        </h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="inspectionRecordForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>System operating pressure (bar)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->system_opt_pressure) && !empty($guhwcri->system_opt_pressure) ? $guhwcri->system_opt_pressure : '') }}" name="system_opt_pressure" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating pressure of expansion vassel (bar)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->opt_presure_exp_vsl) && !empty($guhwcri->opt_presure_exp_vsl) ? $guhwcri->opt_presure_exp_vsl : '') }}" name="opt_presure_exp_vsl" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating pressure of expansion valve (bar)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->opt_presure_exp_vlv) && !empty($guhwcri->opt_presure_exp_vlv) ? $guhwcri->opt_presure_exp_vlv : '') }}" name="opt_presure_exp_vlv" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating temperature of temperature relief valve (C)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->tem_relief_vlv) && !empty($guhwcri->tem_relief_vlv) ? $guhwcri->tem_relief_vlv : '') }}" name="tem_relief_vlv" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating temperature (C)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->opt_temperature) && !empty($guhwcri->opt_temperature) ? $guhwcri->opt_temperature : '') }}" name="opt_temperature" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Pressure of combined temperature and pressure of relief valve (bar)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->combined_temp_presr) && !empty($guhwcri->combined_temp_presr) ? $guhwcri->combined_temp_presr : '') }}" name="combined_temp_presr" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Maximum primary circuit pressure (bar)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->max_circuit_presr) && !empty($guhwcri->max_circuit_presr) ? $guhwcri->max_circuit_presr : '') }}" name="max_circuit_presr" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flow temperature (indirectly heated vassel) (C)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->flow_temp) && !empty($guhwcri->flow_temp) ? $guhwcri->flow_temp : '') }}" name="flow_temp" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            
                            
                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Discharge Pipework (D1) -  relief valve of tundish</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Normal size of D1 (mm)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->d1_mormal_size) && !empty($guhwcri->d1_mormal_size) ? $guhwcri->d1_mormal_size : '') }}" name="d1_mormal_size" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Length of D1 (mm)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->d1_length) && !empty($guhwcri->d1_length) ? $guhwcri->d1_length : '') }}" name="d1_length" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Number of discharges</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->d1_discharges_no) && !empty($guhwcri->d1_discharges_no) ? $guhwcri->d1_discharges_no : '') }}" name="d1_discharges_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Size of manifold, if more than one discharge</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->d1_manifold_size) && !empty($guhwcri->d1_manifold_size) ? $guhwcri->d1_manifold_size : '') }}" name="d1_manifold_size" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Is tundish installed within the same location as the hot water storage vassel</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d1itiwtsl_yes" name="d1_is_tundish_install_same_location" {{ (isset($guhwcri->d1_is_tundish_install_same_location) && $guhwcri->d1_is_tundish_install_same_location == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d1itiwtsl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d1itiwtsl_no" name="d1_is_tundish_install_same_location" {{ (isset($guhwcri->d1_is_tundish_install_same_location) && $guhwcri->d1_is_tundish_install_same_location == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d1itiwtsl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Is the tundish visible</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d1ittv_yes" name="d1_is_tundish_visible" {{ (isset($guhwcri->d1_is_tundish_visible) && $guhwcri->d1_is_tundish_visible == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d1ittv_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d1ittv_no" name="d1_is_tundish_visible" {{ (isset($guhwcri->d1_is_tundish_visible) && $guhwcri->d1_is_tundish_visible == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d1ittv_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Is automatic means of identifying discharge installed</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d1iamoidi_yes" name="d1_is_auto_dis_intall" {{ (isset($guhwcri->d1_is_auto_dis_intall) && $guhwcri->d1_is_auto_dis_intall == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d1iamoidi_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d1iamoidi_no" name="d1_is_auto_dis_intall" {{ (isset($guhwcri->d1_is_auto_dis_intall) && $guhwcri->d1_is_auto_dis_intall == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d1iamoidi_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Discharge Pipework (D2) -  tundish to point of termination</h2>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Norminal size of D2 (mm)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->d2_mormal_size) && !empty($guhwcri->d2_mormal_size) ? $guhwcri->d2_mormal_size : '') }}" name="d2_mormal_size" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Pipework Material</x-base.form-label>
                                <x-base.form-input value="{{ (isset($guhwcri->d2_pipework_material) && !empty($guhwcri->d2_pipework_material) ? $guhwcri->d2_pipework_material : '') }}" name="d2_pipework_material" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Does pipework have a minimum vertical length of 300mm from tundish</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2dphamvloft_yes" name="d2_minimum_v_length" {{ (isset($guhwcri->d2_minimum_v_length) && $guhwcri->d2_minimum_v_length == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2dphamvloft_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2dphamvloft_no" name="d2_minimum_v_length" {{ (isset($guhwcri->d2_minimum_v_length) && $guhwcri->d2_minimum_v_length == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2dphamvloft_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Does the pipework fall continuously to point of termination</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2dtpfctpot_yes" name="d2_fall_continuously" {{ (isset($guhwcri->d2_fall_continuously) && $guhwcri->d2_fall_continuously == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2dtpfctpot_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2dtpfctpot_no" name="d2_fall_continuously" {{ (isset($guhwcri->d2_fall_continuously) && $guhwcri->d2_fall_continuously == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2dtpfctpot_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Method of termination</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2mot_Gully" name="d2_termination_method" {{ (isset($guhwcri->d2_termination_method) && $guhwcri->d2_termination_method == 'Gully' ? 'Checked' : '') }} value="Gully" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2mot_Gully" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Gully
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2mot_Low_Level" name="d2_termination_method" {{ (isset($guhwcri->d2_termination_method) && $guhwcri->d2_termination_method == 'Low Level' ? 'Checked' : '') }} value="Low Level" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2mot_Low_Level" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Low Level
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2mot_Soil_Stack" name="d2_termination_method" {{ (isset($guhwcri->d2_termination_method) && $guhwcri->d2_termination_method == 'Soil Stack' ? 'Checked' : '') }} value="Soil Stack" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2mot_Soil_Stack" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="warning">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Soil Stack
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2mot_high_Level" name="d2_termination_method" {{ (isset($guhwcri->d2_termination_method) && $guhwcri->d2_termination_method == 'High Level' ? 'Checked' : '') }} value="High Level" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2mot_high_Level" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            High Level
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Method of termination satisfactory</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2mots_yes" name="d2_termination_satisfactory" {{ (isset($guhwcri->d2_termination_satisfactory) && $guhwcri->d2_termination_satisfactory == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2mots_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_d2mots_no" name="d2_termination_satisfactory" {{ (isset($guhwcri->d2_termination_satisfactory) && $guhwcri->d2_termination_satisfactory == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_d2mots_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12">
                                <x-base.form-label class="mb-1">Comments</x-base.form-label>
                                <x-base.form-textarea name="comments" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($guhwcri->comments) && !empty($guhwcri->comments) ? $guhwcri->comments : '') }}</x-base.form-textarea>
                            </div>
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="0" class="form-wizard-previous-btn" variant="secondary">
                                <x-base.lucide class="h-5 w-5 mr-2" icon="move-left"/>Previous
                            </x-base.button>
                            <x-base.button type="button" data-appliance="1" class="form-wizard-next-btn ml-auto" variant="linkedin" >
                                Save & Continue<x-base.lucide class="theIcon h-5 w-5 ml-2" icon="move-right"/>
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
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
                                $inspectionDeate = (isset($guhwcr->inspection_date) && !empty($guhwcr->inspection_date) ? date('d-m-Y', strtotime($guhwcr->inspection_date)) : date('d-m-Y'));
                                $nextInspectionDate = (isset($guhwcr->next_inspection_date) && !empty($guhwcr->next_inspection_date) ? date('d-m-Y', strtotime($guhwcr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
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
                                <x-base.form-input value="{{ (isset($guhwcr->received_by) && !empty($guhwcr->received_by) ? $guhwcr->received_by : '') }}" type="text" name="received_by" class="w-full h-[35px] rounded-[3px]" placeholder=""/>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Relation</x-base.form-label>
                                <x-base.tom-select class="w-full" name="relation_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($relations->count() > 0)
                                        @foreach($relations as $option)
                                            <option {{ (isset($guhwcr->relation_id) && $guhwcr->relation_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
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
    @vite('resources/js/app/records/unvented_hot_water_cylinders.js')
@endPushOnce