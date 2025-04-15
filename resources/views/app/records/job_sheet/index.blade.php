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
                                <x-base.lucide style="display: {{ (isset($gjsrd->id) && $gjsrd->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gjsrd->id) && $gjsrd->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/> 
                                <span class="info">
                                    Job Sheet
                                </span>
                                <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_4" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gjsr->has_signatures) && $gjsr->has_signatures ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gjsr->has_signatures) && $gjsr->has_signatures ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>  
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
                                <x-base.form-input value="{{ (isset($job->customer->full_name) ? $job->customer->full_name : '') }}" name="customer_name" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Customer Full Name" 
                                    oninput="this.value = this.value.toUpperCase()" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Company</x-base.form-label>
                                <x-base.form-input value="{{ (isset($job->customer->company_name) ? $job->customer->company_name : '') }}" name="customer_company" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Customer Company" 
                                    oninput="this.value = this.value.toUpperCase()" />
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
                            <x-base.lucide style="display: {{ (isset($gjsrd->id) && $gjsrd->id > 0 ? 'none' : 'block') }};" class="w-4 h-4 mr-2 text-danger unsavedIcon" icon="x-circle"/>    
                            <x-base.lucide style="display: {{ (isset($gjsrd->id) && $gjsrd->id > 0 ? 'block' : 'none') }};" class="w-4 h-4 mr-2 text-success savedIcon" icon="check-circle"/> 
                            <span class="info">
                                Job Sheet
                            </span>
                        </h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="jobSheetDetailsForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12">
                                <x-base.form-label>Date</x-base.form-label>
                                <x-base.litepicker value="{{ (isset($gjsrd->date) ? $gjsrd->date : '') }}" name="date" class="w-full h-[35px] rounded-[3px]" data-format="DD-MM-YYYY" data-single-mode="true" />
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Job Notes</x-base.form-label>
                                <x-base.form-textarea name="job_note" class="w-full h-[70px] rounded-[3px]" placeholder="">{{ (isset($gjsrd->job_note) && !empty($gjsrd->job_note) ? $gjsrd->job_note : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Spares Required</x-base.form-label>
                                <x-base.form-textarea name="spares_required" class="w-full h-[70px] rounded-[3px]" placeholder="">{{ (isset($gjsrd->spares_required) && !empty($gjsrd->spares_required) ? $gjsrd->spares_required : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Job Ref</x-base.form-label>
                                <x-base.form-textarea name="job_ref" class="w-full h-[70px] rounded-[3px]" placeholder="">{{ (isset($gjsrd->job_ref) && !empty($gjsrd->job_ref) ? $gjsrd->job_ref : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Arrival Time</x-base.form-label>
                                <x-base.form-textarea name="arrival_time" class="w-full h-[70px] rounded-[3px]" placeholder="">{{ (isset($gjsrd->arrival_time) && !empty($gjsrd->arrival_time) ? $gjsrd->arrival_time : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Departure Time</x-base.form-label>
                                <x-base.form-textarea name="departure_time" class="w-full h-[70px] rounded-[3px]" placeholder="">{{ (isset($gjsrd->departure_time) && !empty($gjsrd->departure_time) ? $gjsrd->departure_time : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Hours Used</x-base.form-label>
                                <x-base.form-textarea name="hours_used" class="w-full h-[70px] rounded-[3px]" placeholder="">{{ (isset($gjsrd->hours_used) && !empty($gjsrd->hours_used) ? $gjsrd->hours_used : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Awaiting Parts</x-base.form-label>
                                <x-base.form-textarea name="awaiting_parts" class="w-full h-[70px] rounded-[3px]" placeholder="">{{ (isset($gjsrd->awaiting_parts) && !empty($gjsrd->awaiting_parts) ? $gjsrd->awaiting_parts : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Job Completed</x-base.form-label>
                                <div class="flex justify-start items-center">
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_jbcmpl_yes" name="job_completed" {{ (isset($gjsrd->job_completed) && $gjsrd->job_completed == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_jbcmpl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            YES
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_jbcmpl_no" name="job_completed" {{ (isset($gjsrd->job_completed) && $gjsrd->job_completed == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_jbcmpl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            NO
                                        </x-base.button>
                                    </div>
                                    <div class="radioItem mr-[3px]">
                                        <input id="gwn_jbcmpl_na" name="job_completed" {{ (isset($gjsrd->job_completed) && $gjsrd->job_completed == 'N/A' ? 'Checked' : '') }} value="N/A" type="radio" class="absolute w-0 h-0 opacity-0" />
                                        <x-base.button as="label" for="gwn_jbcmpl_na" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="pending">
                                            <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12">
                                <div class="customeUploads border-2 border-dashed border-slate-500 flex items-center text-center min-h-[150px] rounded-[5px] p-[20px]">
                                    <label for="job_sheet_files" class="text-center upload-message my-[3em] relative w-full cursor-pointer">
                                        <div class="customeUploadsContent" style="display: {{ $gjsrdc->count() > 0 ? 'none' : 'block' }};">
                                            <span class="text-lg font-medium">
                                                Drop files here or click to upload.
                                            </span><br/>
                                            <span class="text-gray-600">
                                                Upload maximum 10 files. Allowed file types are<br/>
                                                images, excel, document, & PDF.
                                            </span><br/>
                                        </div>
                                        <div id="uploadedFileWrap" class="flex justify-center items-center" style="display: {{ $gjsrdc->count() > 0 ? 'flex' : 'none' }};">
                                            @if($gjsrdc->count())
                                                @foreach($gjsrdc as $doc)
                                                    @if($doc->doc_url)
                                                        @if(in_array($doc->mime_type, ['image/gif', 'image/jpeg', 'image/png', 'image/jpg']))
                                                            <a id="gjsr_doc_{{ $doc->id }}" href="{{ $doc->doc_url }}" target="_blank" class="relative inline-flex items-center justify-center image-fit h-[80px] w-[80px] bg-success bg-opacity-10 rounded-[3px] overflow-hidden mr-1 mb-1"><button data-id="{{ $doc->id }}" class="delete-doc absolute z-10 right-0 top-0 w-[25px] h-[25px] bg-danger text-white rounded-none rounded-bl-[3px] inline-flex items-center justify-center" type="button"><x-base.lucide class="h-3 w-3" icon="x"/></button><img class="rounded-[3px]" src="{{ $doc->doc_url }}"></a>
                                                        @else
                                                            <a id="gjsr_doc_{{ $doc->id }}" href="{{ $doc->doc_url }}" target="_blank" class="relative inline-flex items-center justify-center h-[80px] w-[80px] bg-success bg-opacity-10 rounded-[3px] overflow-hidden mr-1 mb-1"><button data-id="{{ $doc->id }}" class="delete-doc absolute z-10 right-0 top-0 w-[25px] h-[25px] bg-danger text-white rounded-none rounded-bl-[3px] inline-flex items-center justify-center" type="button"><x-base.lucide class="h-3 w-3" icon="x"/></button><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="file-text" class="lucide lucide-file-text stroke-1.5 h-8 w-8 text-success"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg></a>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </label>
                                    <input type="file" multiple id="job_sheet_files" name="job_sheet_files[]" accept="image/*,.pdf,.xl,.xlsx,.xls,.doc,.docx,.txt,.ppt,.pptx" class="w-0 h-0 opacity-0 absolute left-0 top-0"/>
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
                                $inspectionDeate = (isset($gjsr->inspection_date) && !empty($gjsr->inspection_date) ? date('d-m-Y', strtotime($gjsr->inspection_date)) : date('d-m-Y'));
                            @endphp
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Today's Date</x-base.form-label>
                                <x-base.litepicker name="inspection_date" id="date_issued" value="{{ $inspectionDeate }}" class="w-full h-[35px] rounded-[3px]" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Received By</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gjsr->received_by) && !empty($gjsr->received_by) ? $gjsr->received_by : '') }}" type="text" name="received_by" class="w-full h-[35px] rounded-[3px]" placeholder=""/>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label class="mb-1">Relation</x-base.form-label>
                                <x-base.tom-select class="w-full" name="relation_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($relations->count() > 0)
                                        @foreach($relations as $option)
                                            <option {{ (isset($gjsr->relation_id) && $gjsr->relation_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
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
    @vite('resources/js/app/records/job_sheet_record.js')
@endPushOnce