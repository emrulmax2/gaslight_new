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
                                <x-base.lucide style="display: {{ (isset($gsra1->id) && $gsra1->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsra1->id) && $gsra1->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/> 
                                <span class="info">
                                    @if((isset($gsra1->make->name) && !empty($gsra1->make->name)) || (isset($gsra1->type->name) && !empty($gsra1->type->name)))
                                        {{ (isset($gsra1->make->name) && !empty($gsra1->make->name) ? $gsra1->make->name.' ' : '') }}
                                        {{ (isset($gsra1->type->name) && !empty($gsra1->type->name) ? $gsra1->type->name.' ' : '') }}
                                    @else
                                        Appliance
                                    @endif
                                </span>
                                <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_4" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gsr->has_signatures) && $gsr->has_signatures ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsr->has_signatures) && $gsr->has_signatures ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>  
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
                                <x-base.form-input value="{{ (isset($job->customer->full_name) ? $job->customer->full_name : '') }}" name="customer_name" class="w-full h-[35px] rounded-[3px] cap-fullname" type="text" placeholder="Customer Full Name" 
                                     />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Company</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->company_name) ? $job->customer->company_name : '') }}" name="customer_company" class="w-full h-[35px] rounded-[3px] cap-fullname" type="text" placeholder="Customer Company" 
                                     />
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
                            <x-base.lucide style="display: {{ (isset($gsra1->id) && $gsra1->id > 0 ? 'none' : 'block') }};" class="w-4 h-4 mr-2 text-danger unsavedIcon" icon="x-circle"/>    
                            <x-base.lucide style="display: {{ (isset($gsra1->id) && $gsra1->id > 0 ? 'block' : 'none') }};" class="w-4 h-4 mr-2 text-success savedIcon" icon="check-circle"/> 
                            <span class="info">
                                @if((isset($gsra1->make->name) && !empty($gsra1->make->name)) || (isset($gsra1->type->name) && !empty($gsra1->type->name)))
                                    {{ (isset($gsra1->make->name) && !empty($gsra1->make->name) ? ' - '.$gsra1->make->name.' ' : '') }}
                                    {{ (isset($gsra1->type->name) && !empty($gsra1->type->name) ? $gsra1->type->name.' ' : '') }}
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
                                <x-base.form-label>Location</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[1][appliance_location_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($locations->count() > 0)
                                        @foreach($locations as $option)
                                            <option {{ (isset($gsra1->appliance_location_id) && $gsra1->appliance_location_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Make</x-base.form-label>
                                <x-base.tom-select class="w-full applianceMake" name="app[1][boiler_brand_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($boilers->count() > 0)
                                        @foreach($boilers as $option)
                                            <option {{ (isset($gsra1->boiler_brand_id) && $gsra1->boiler_brand_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Model</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra1->model) ? $gsra1->model : '') }}" name="app[1][model]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Model" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type</x-base.form-label>
                                <x-base.tom-select class="w-full applianceType" name="app[1][appliance_type_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($types->count() > 0)
                                        @foreach($types as $option)
                                            <option {{ (isset($gsra1->appliance_type_id) && $gsra1->appliance_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>GC Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra1->gc_no) ? $gsra1->gc_no : '') }}" name="app[1][gc_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="GC Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Serial Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra1->serial_no) ? $gsra1->serial_no : '') }}" name="app[1][serial_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating Pressure (mbar) or Heat Input (KW/h) or (BTU/h)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra1->opt_pressure) ? $gsra1->opt_pressure : '') }}" name="app[1][opt_pressure]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Operating Pressure" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Rented Accommodation</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_racc_yes" name="app[1][rented_accommodation]" {{ (isset($gsra1->rented_accommodation) && $gsra1->rented_accommodation == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_racc_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_racc_no" name="app[1][rented_accommodation]" {{ (isset($gsra1->rented_accommodation) && $gsra1->rented_accommodation == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_racc_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type of Work Carried Out</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_towco_yes" name="app[1][type_of_work_carried_out]" {{ (isset($gsra1->type_of_work_carried_out) && $gsra1->type_of_work_carried_out == 'Service' ? 'Checked' : '') }} value="Service" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_towco_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Service
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_towco_no" name="app[1][type_of_work_carried_out]" {{ (isset($gsra1->type_of_work_carried_out) && $gsra1->type_of_work_carried_out == 'Maintenance' ? 'Checked' : '') }} value="Maintenance" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_towco_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Maintenance
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_towco_na" name="app[1][type_of_work_carried_out]" {{ (isset($gsra1->type_of_work_carried_out) && $gsra1->type_of_work_carried_out == 'Call Out' ? 'Checked' : '') }} value="Call Out" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_towco_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Call Out
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>If a gas test has been carried out, was this a pass or fail</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tcro_yes" name="app[1][test_carried_out]" {{ (isset($gsra1->test_carried_out) && $gsra1->test_carried_out == 'Pass' ? 'Checked' : '') }} value="Pass" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tcro_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Pass
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tcro_no" name="app[1][test_carried_out]" {{ (isset($gsra1->test_carried_out) && $gsra1->test_carried_out == 'Fail' ? 'Checked' : '') }} value="Fail" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tcro_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            Fail
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_tcro_na" name="app[1][test_carried_out]" {{ (isset($gsra1->test_carried_out) && $gsra1->test_carried_out == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_tcro_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Is electrical bonding (where required satisfactory)</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_iseb_yes" name="app[1][is_electricial_bonding]" {{ (isset($gsra1->is_electricial_bonding) && $gsra1->is_electricial_bonding == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_iseb_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_iseb_no" name="app[1][is_electricial_bonding]" {{ (isset($gsra1->is_electricial_bonding) && $gsra1->is_electricial_bonding == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_iseb_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_iseb_na" name="app[1][is_electricial_bonding]" {{ (isset($gsra1->is_electricial_bonding) && $gsra1->is_electricial_bonding == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_iseb_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 pt-2">
                                <x-base.form-label>Inital (low) Combustion Analyser Reading</x-base.form-label>
                                <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gsra1->low_analyser_ratio) ? $gsra1->low_analyser_ratio : '') }}" name="app[1][low_analyser_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                                <x-base.form-input value="{{ (isset($gsra1->low_co) ? $gsra1->low_co : '') }}" name="app[1][low_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                                <x-base.form-input value="{{ (isset($gsra1->low_co2) ? $gsra1->low_co2 : '') }}" name="app[1][low_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 pt-2">
                                <x-base.form-label>Final (high) Combustion Analyser Reading</x-base.form-label>
                                <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gsra1->high_analyser_ratio) ? $gsra1->high_analyser_ratio : '') }}" name="app[1][high_analyser_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                                <x-base.form-input value="{{ (isset($gsra1->high_co) ? $gsra1->high_co : '') }}" name="app[1][high_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                                <x-base.form-input value="{{ (isset($gsra1->high_co2) ? $gsra1->high_co2 : '') }}" name="app[1][high_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Heat Exchanger</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hex_yes" name="app[1][heat_exchanger]" {{ (isset($gsra1->heat_exchanger) && $gsra1->heat_exchanger == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hex_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hex_no" name="app[1][heat_exchanger]" {{ (isset($gsra1->heat_exchanger) && $gsra1->heat_exchanger == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hex_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_hex_na" name="app[1][heat_exchanger]" {{ (isset($gsra1->heat_exchanger) && $gsra1->heat_exchanger == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_hex_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Heat Exchanger: Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][heat_exchanger_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->heat_exchanger_detail) && !empty($gsra1->heat_exchanger_detail) ? $gsra1->heat_exchanger_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Burner / Injectors</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_isbi_yes" name="app[1][burner_injectors]" {{ (isset($gsra1->burner_injectors) && $gsra1->burner_injectors == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_isbi_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_isbi_no" name="app[1][burner_injectors]" {{ (isset($gsra1->burner_injectors) && $gsra1->burner_injectors == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_isbi_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_isbi_na" name="app[1][burner_injectors]" {{ (isset($gsra1->burner_injectors) && $gsra1->burner_injectors == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_isbi_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Burner / Injectors: Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][burner_injectors_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->burner_injectors_detail) && !empty($gsra1->burner_injectors_detail) ? $gsra1->burner_injectors_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flame Picture</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_isfp_yes" name="app[1][flame_picture]" {{ (isset($gsra1->flame_picture) && $gsra1->flame_picture == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_isfp_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_isfp_no" name="app[1][flame_picture]" {{ (isset($gsra1->flame_picture) && $gsra1->flame_picture == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_isfp_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_isfp_na" name="app[1][flame_picture]" {{ (isset($gsra1->flame_picture) && $gsra1->flame_picture == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_isfp_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][flame_picture_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->flame_picture_detail) && !empty($gsra1->flame_picture_detail) ? $gsra1->flame_picture_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Ignition</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_igtn_yes" name="app[1][ignition]" {{ (isset($gsra1->ignition) && $gsra1->ignition == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_igtn_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_igtn_no" name="app[1][ignition]" {{ (isset($gsra1->ignition) && $gsra1->ignition == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_igtn_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_igtn_na" name="app[1][ignition]" {{ (isset($gsra1->ignition) && $gsra1->ignition == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_igtn_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][ignition_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->ignition_detail) && !empty($gsra1->ignition_detail) ? $gsra1->ignition_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Electrics</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_elec_yes" name="app[1][electrics]" {{ (isset($gsra1->electrics) && $gsra1->electrics == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_elec_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_elec_no" name="app[1][electrics]" {{ (isset($gsra1->electrics) && $gsra1->electrics == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_elec_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_elec_na" name="app[1][electrics]" {{ (isset($gsra1->electrics) && $gsra1->electrics == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_elec_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][electrics_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->electrics_detail) && !empty($gsra1->electrics_detail) ? $gsra1->electrics_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Controls</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_ctrls_yes" name="app[1][controls]" {{ (isset($gsra1->controls) && $gsra1->controls == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_ctrls_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_ctrls_no" name="app[1][controls]" {{ (isset($gsra1->controls) && $gsra1->controls == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_ctrls_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_ctrls_na" name="app[1][controls]" {{ (isset($gsra1->controls) && $gsra1->controls == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_ctrls_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][controls_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->controls_detail) && !empty($gsra1->controls_detail) ? $gsra1->controls_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Leaks Gas / Water</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_lgw_yes" name="app[1][leak_gas_water]" {{ (isset($gsra1->leak_gas_water) && $gsra1->leak_gas_water == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_lgw_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_lgw_no" name="app[1][leak_gas_water]" {{ (isset($gsra1->leak_gas_water) && $gsra1->leak_gas_water == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_lgw_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_lgw_na" name="app[1][leak_gas_water]" {{ (isset($gsra1->leak_gas_water) && $gsra1->leak_gas_water == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_lgw_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][leak_gas_water_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->leak_gas_water_detail) && !empty($gsra1->leak_gas_water_detail) ? $gsra1->leak_gas_water_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Seals</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_seals_yes" name="app[1][seals]" {{ (isset($gsra1->seals) && $gsra1->seals == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_seals_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_seals_no" name="app[1][seals]" {{ (isset($gsra1->seals) && $gsra1->seals == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_seals_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_seals_na" name="app[1][seals]" {{ (isset($gsra1->seals) && $gsra1->seals == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_seals_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][seals_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->seals_detail) && !empty($gsra1->seals_detail) ? $gsra1->seals_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Pipework</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_pipw_yes" name="app[1][pipework]" {{ (isset($gsra1->pipework) && $gsra1->pipework == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_pipw_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_pipw_no" name="app[1][pipework]" {{ (isset($gsra1->pipework) && $gsra1->pipework == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_pipw_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_pipw_na" name="app[1][pipework]" {{ (isset($gsra1->pipework) && $gsra1->pipework == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_pipw_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][pipework_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->pipework_detail) && !empty($gsra1->pipework_detail) ? $gsra1->pipework_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Fans</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_fan_yes" name="app[1][fans]" {{ (isset($gsra1->fans) && $gsra1->fans == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_fan_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_fan_no" name="app[1][fans]" {{ (isset($gsra1->fans) && $gsra1->fans == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_fan_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_fan_na" name="app[1][fans]" {{ (isset($gsra1->fans) && $gsra1->fans == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_fan_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][fans_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->fans_detail) && !empty($gsra1->fans_detail) ? $gsra1->fans_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Fireplace</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_fire_yes" name="app[1][fireplace]" {{ (isset($gsra1->fireplace) && $gsra1->fireplace == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_fire_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_fire_no" name="app[1][fireplace]" {{ (isset($gsra1->fireplace) && $gsra1->fireplace == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_fire_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_fire_na" name="app[1][fireplace]" {{ (isset($gsra1->fireplace) && $gsra1->fireplace == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_fire_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][fireplace_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->fireplace_detail) && !empty($gsra1->fireplace_detail) ? $gsra1->fireplace_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Closure Plate & PRS10 Tape</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_prs10_yes" name="app[1][closure_plate]" {{ (isset($gsra1->closure_plate) && $gsra1->closure_plate == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_prs10_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_prs10_no" name="app[1][closure_plate]" {{ (isset($gsra1->closure_plate) && $gsra1->closure_plate == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_prs10_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_prs10_na" name="app[1][closure_plate]" {{ (isset($gsra1->closure_plate) && $gsra1->closure_plate == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_prs10_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][closure_plate_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->closure_plate_detail) && !empty($gsra1->closure_plate_detail) ? $gsra1->closure_plate_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Allowable Location</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_allwl_yes" name="app[1][allowable_location]" {{ (isset($gsra1->allowable_location) && $gsra1->allowable_location == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_allwl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_allwl_no" name="app[1][allowable_location]" {{ (isset($gsra1->allowable_location) && $gsra1->allowable_location == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_allwl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_allwl_na" name="app[1][allowable_location]" {{ (isset($gsra1->allowable_location) && $gsra1->allowable_location == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_allwl_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][allowable_location_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->allowable_location_detail) && !empty($gsra1->allowable_location_detail) ? $gsra1->allowable_location_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Boiler Ratio</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_brat_yes" name="app[1][boiler_ratio]" {{ (isset($gsra1->boiler_ratio) && $gsra1->boiler_ratio == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_brat_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_brat_no" name="app[1][boiler_ratio]" {{ (isset($gsra1->boiler_ratio) && $gsra1->boiler_ratio == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_brat_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_brat_na" name="app[1][boiler_ratio]" {{ (isset($gsra1->boiler_ratio) && $gsra1->boiler_ratio == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_brat_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][boiler_ratio_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->boiler_ratio_detail) && !empty($gsra1->boiler_ratio_detail) ? $gsra1->boiler_ratio_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Stability</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_stbl_yes" name="app[1][stability]" {{ (isset($gsra1->stability) && $gsra1->stability == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_stbl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_stbl_no" name="app[1][stability]" {{ (isset($gsra1->stability) && $gsra1->stability == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_stbl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_stbl_na" name="app[1][stability]" {{ (isset($gsra1->stability) && $gsra1->stability == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_stbl_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][stability_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->stability_detail) && !empty($gsra1->stability_detail) ? $gsra1->stability_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Return Air / Plenum</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_rapl_yes" name="app[1][return_air_ple]" {{ (isset($gsra1->return_air_ple) && $gsra1->return_air_ple == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_rapl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_rapl_no" name="app[1][return_air_ple]" {{ (isset($gsra1->return_air_ple) && $gsra1->return_air_ple == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_rapl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_rapl_na" name="app[1][return_air_ple]" {{ (isset($gsra1->return_air_ple) && $gsra1->return_air_ple == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_rapl_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][return_air_ple_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->return_air_ple_detail) && !empty($gsra1->return_air_ple_detail) ? $gsra1->return_air_ple_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Ventillation</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_ventl_yes" name="app[1][ventillation]" {{ (isset($gsra1->ventillation) && $gsra1->ventillation == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_ventl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_ventl_no" name="app[1][ventillation]" {{ (isset($gsra1->ventillation) && $gsra1->ventillation == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_ventl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_ventl_na" name="app[1][ventillation]" {{ (isset($gsra1->ventillation) && $gsra1->ventillation == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_ventl_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][ventillation_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->ventillation_detail) && !empty($gsra1->ventillation_detail) ? $gsra1->ventillation_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Termination</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_flut_yes" name="app[1][flue_termination]" {{ (isset($gsra1->flue_termination) && $gsra1->flue_termination == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_flut_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_flut_no" name="app[1][flue_termination]" {{ (isset($gsra1->flue_termination) && $gsra1->flue_termination == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_flut_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_flut_na" name="app[1][flue_termination]" {{ (isset($gsra1->flue_termination) && $gsra1->flue_termination == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_flut_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][flue_termination_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->flue_termination_detail) && !empty($gsra1->flue_termination_detail) ? $gsra1->flue_termination_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Smoke Pellet Flue Flow Test</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_spfft_yes" name="app[1][smoke_pellet_flue_flow]" {{ (isset($gsra1->smoke_pellet_flue_flow) && $gsra1->smoke_pellet_flue_flow == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_spfft_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_spfft_no" name="app[1][smoke_pellet_flue_flow]" {{ (isset($gsra1->smoke_pellet_flue_flow) && $gsra1->smoke_pellet_flue_flow == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_spfft_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_spfft_na" name="app[1][smoke_pellet_flue_flow]" {{ (isset($gsra1->smoke_pellet_flue_flow) && $gsra1->smoke_pellet_flue_flow == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_spfft_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][smoke_pellet_flue_flow_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->smoke_pellet_flue_flow_detail) && !empty($gsra1->smoke_pellet_flue_flow_detail) ? $gsra1->smoke_pellet_flue_flow_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Smoke Match Spillage Test</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_smst_yes" name="app[1][smoke_pellet_spillage]" {{ (isset($gsra1->smoke_pellet_spillage) && $gsra1->smoke_pellet_spillage == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_smst_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_smst_no" name="app[1][smoke_pellet_spillage]" {{ (isset($gsra1->smoke_pellet_spillage) && $gsra1->smoke_pellet_spillage == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_smst_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_smst_na" name="app[1][smoke_pellet_spillage]" {{ (isset($gsra1->smoke_pellet_spillage) && $gsra1->smoke_pellet_spillage == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_smst_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][smoke_pellet_spillage_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->smoke_pellet_spillage_detail) && !empty($gsra1->smoke_pellet_spillage_detail) ? $gsra1->smoke_pellet_spillage_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Working Pressure</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_wprs_yes" name="app[1][working_pressure]" {{ (isset($gsra1->working_pressure) && $gsra1->working_pressure == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_wprs_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_wprs_no" name="app[1][working_pressure]" {{ (isset($gsra1->working_pressure) && $gsra1->working_pressure == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_wprs_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_wprs_na" name="app[1][working_pressure]" {{ (isset($gsra1->working_pressure) && $gsra1->working_pressure == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_wprs_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][working_pressure_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->working_pressure_detail) && !empty($gsra1->working_pressure_detail) ? $gsra1->working_pressure_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Safety Devices</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_sftd_yes" name="app[1][savety_devices]" {{ (isset($gsra1->savety_devices) && $gsra1->savety_devices == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_sftd_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_sftd_no" name="app[1][savety_devices]" {{ (isset($gsra1->savety_devices) && $gsra1->savety_devices == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_sftd_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_sftd_na" name="app[1][savety_devices]" {{ (isset($gsra1->savety_devices) && $gsra1->savety_devices == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_sftd_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][savety_devices_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->savety_devices_detail) && !empty($gsra1->savety_devices_detail) ? $gsra1->savety_devices_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Gas Tightness test performed</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_gstn_yes" name="app[1][gas_tightness]" {{ (isset($gsra1->gas_tightness) && $gsra1->gas_tightness == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_gstn_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_gstn_no" name="app[1][gas_tightness]" {{ (isset($gsra1->gas_tightness) && $gsra1->gas_tightness == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_gstn_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_gstn_na" name="app[1][gas_tightness]" {{ (isset($gsra1->gas_tightness) && $gsra1->gas_tightness == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_gstn_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][gas_tightness_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->gas_tightness_detail) && !empty($gsra1->gas_tightness_detail) ? $gsra1->gas_tightness_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Expansion Vassel checked / recharged?</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_exvcr_yes" name="app[1][expansion_vassel_checked]" {{ (isset($gsra1->expansion_vassel_checked) && $gsra1->expansion_vassel_checked == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_exvcr_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_exvcr_no" name="app[1][expansion_vassel_checked]" {{ (isset($gsra1->expansion_vassel_checked) && $gsra1->expansion_vassel_checked == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_exvcr_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_exvcr_na" name="app[1][expansion_vassel_checked]" {{ (isset($gsra1->expansion_vassel_checked) && $gsra1->expansion_vassel_checked == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_exvcr_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][expansion_vassel_checked_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->expansion_vassel_checked_detail) && !empty($gsra1->expansion_vassel_checked_detail) ? $gsra1->expansion_vassel_checked_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Other (regulations etc.)</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_oreg_yes" name="app[1][other_regulations]" {{ (isset($gsra1->other_regulations) && $gsra1->other_regulations == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_oreg_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_oreg_no" name="app[1][other_regulations]" {{ (isset($gsra1->other_regulations) && $gsra1->other_regulations == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_oreg_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_oreg_na" name="app[1][other_regulations]" {{ (isset($gsra1->other_regulations) && $gsra1->other_regulations == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_oreg_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-8">
                                <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                                <x-base.form-textarea name="app[1][other_regulations_detail]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->other_regulations_detail) && !empty($gsra1->other_regulations_detail) ? $gsra1->other_regulations_detail : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Is the Installation and appliance safe to use?</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_iastu_yes" name="app[1][is_safe_to_use]" {{ (isset($gsra1->is_safe_to_use) && $gsra1->is_safe_to_use == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_iastu_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_iastu_no" name="app[1][is_safe_to_use]" {{ (isset($gsra1->is_safe_to_use) && $gsra1->is_safe_to_use == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_iastu_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_iastu_na" name="app[1][is_safe_to_use]" {{ (isset($gsra1->is_safe_to_use) && $gsra1->is_safe_to_use == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_iastu_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Has the installation been carried out to the relevant standard / manufacturers instructions?</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_smif_yes" name="app[1][instruction_followed]" {{ (isset($gsra1->instruction_followed) && $gsra1->instruction_followed == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_smif_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_smif_no" name="app[1][instruction_followed]" {{ (isset($gsra1->instruction_followed) && $gsra1->instruction_followed == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_smif_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12">
                                <x-base.form-label class="mb-1">Necessary remedial work required</x-base.form-label>
                                <x-base.form-textarea name="app[1][work_required_note]" class="w-full h-[60px] rounded-[3px]" placeholder="Details">{{ (isset($gsra1->work_required_note) && !empty($gsra1->work_required_note) ? $gsra1->work_required_note : '') }}</x-base.form-textarea>
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
                                $inspectionDeate = (isset($gsr->inspection_date) && !empty($gsr->inspection_date) ? date('d-m-Y', strtotime($gsr->inspection_date)) : date('d-m-Y'));
                                $nextInspectionDate = (isset($gsr->next_inspection_date) && !empty($gsr->next_inspection_date) ? date('d-m-Y', strtotime($gsr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
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
                                <x-base.form-input value="{{ (isset($gsr->received_by) && !empty($gsr->received_by) ? $gsr->received_by : '') }}" type="text" name="received_by" class="w-full h-[35px] rounded-[3px]" placeholder=""/>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Relation</x-base.form-label>
                                <x-base.tom-select class="w-full" name="relation_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($relations->count() > 0)
                                        @foreach($relations as $option)
                                            <option {{ (isset($gsr->relation_id) && $gsr->relation_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
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
    @vite('resources/js/app/records/gas_service_record.js')
@endPushOnce