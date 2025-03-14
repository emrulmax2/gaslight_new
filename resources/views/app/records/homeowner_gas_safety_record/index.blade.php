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
                            <x-base.button type="button" data-appliance="1" data-id="step_3" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gsra1->id) && $gsra1->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsra1->id) && $gsra1->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/> 
                                Appliance 1 &nbsp;<span class="info">{{ (isset($gsra1->make->name) && !empty($gsra1->make->name) ? $gsra1->make->name.' ' : '') }}{{ (isset($gsra1->type->name) && !empty($gsra1->type->name) ? $gsra1->type->name.' ' : '') }}</span><x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="2" data-id="step_4" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gsra2->id) && $gsra2->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsra2->id) && $gsra2->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>    
                                Appliance 2 &nbsp;<span class="info">{{ (isset($gsra2->make->name) && !empty($gsra2->make->name) ? $gsra2->make->name.' ' : '') }}{{ (isset($gsra2->type->name) && !empty($gsra2->type->name) ? $gsra2->type->name.' ' : '') }}</span><x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="3" data-id="step_5" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gsra3->id) && $gsra3->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsra3->id) && $gsra3->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>
                                Appliance 3 &nbsp;<span class="info">{{ (isset($gsra3->make->name) && !empty($gsra3->make->name) ? $gsra3->make->name.' ' : '') }}{{ (isset($gsra3->type->name) && !empty($gsra3->type->name) ? $gsra3->type->name.' ' : '') }}</span><x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="4" data-id="step_6" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gsra4->id) && $gsra4->id > 0 ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsra4->id) && $gsra4->id > 0 ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>   
                                Appliance 4 &nbsp;<span class="info">{{ (isset($gsra4->make->name) && !empty($gsra4->make->name) ? $gsra4->make->name.' ' : '') }}{{ (isset($gsra4->type->name) && !empty($gsra4->type->name) ? $gsra4->type->name.' ' : '') }}</span><x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_7" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gsr->has_coalarm) && $gsr->has_coalarm ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsr->has_coalarm) && $gsr->has_coalarm ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>
                                CO Alarm(s) <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_8" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gsr->has_satisfactory_check) && $gsr->has_satisfactory_check ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsr->has_satisfactory_check) && $gsr->has_satisfactory_check ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>
                                Safety Checks <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_9" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
                                <x-base.lucide style="display: {{ (isset($gsr->has_comments) && $gsr->has_comments ? 'none' : 'block') }};" class="w-3.5 h-3.5 mr-2 text-danger unsavedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="x-circle"/>    
                                <x-base.lucide style="display: {{ (isset($gsr->has_comments) && $gsr->has_comments ? 'block' : 'none') }};" class="w-3.5 h-3.5 mr-2 text-success savedIcon absolute left-[9px] top-0 bottom-0 my-auto" icon="check-circle"/>  
                                Comments <x-base.lucide class="w-4 h-4 absolute right-[5px] top-0 bottom-0 my-auto" icon="chevron-right"/>
                            </x-base.button>
                            <x-base.button type="button" data-appliance="0" data-id="step_10" class="form-wizard-step-item relative pr-[25px] pl-[30px] w-full flex items-center justify-start rounded-[3px] cursor-pointer mb-2 [&.active]:text-success [&.active]:border-success flex-wrap">
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
                        <h2 class="text-base font-medium">Job Address Details</h2>
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
                        <h2 class="text-base font-medium">Customer Details</h2>
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
                        <h2 class="text-base font-medium">Appliance 1 <span class="info">{{ (isset($gsra1->make->name) && !empty($gsra1->make->name) ? $gsra1->make->name.' ' : '') }}{{ (isset($gsra1->type->name) && !empty($gsra1->type->name) ? $gsra1->type->name.' ' : '') }}</h2>
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
                                <x-base.form-label>Serial Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra1->serial_no) ? $gsra1->serial_no : '') }}" name="app[1][serial_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>GC Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra1->gc_no) ? $gsra1->gc_no : '') }}" name="app[1][gc_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="GC Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Type</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[1][appliance_flue_type_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($flue_types->count() > 0)
                                        @foreach($flue_types as $option)
                                            <option {{ (isset($gsra1->appliance_flue_type_id) && $gsra1->appliance_flue_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating Pressure (mbar) or Heat Input (KW/h) or (BTU/h)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra1->opt_pressure) ? $gsra1->opt_pressure : '') }}" name="app[1][opt_pressure]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Operating Pressure" />
                            </div>
                            <div class="col-span-12 sm:col-span-4"></div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Safety Devices</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->safety_devices) && $gsra1->safety_devices == 'Yes' ? '1' : '0') }}" id="app_1_sd_yes" name="app[1][safety_devices]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_sd_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->safety_devices) && $gsra1->safety_devices == 'No' ? '1' : '0') }}" id="app_1_sd_no" name="app[1][safety_devices]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_sd_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->safety_devices) && $gsra1->safety_devices == 'N/A' ? '1' : '0') }}" id="app_1_sd_na" name="app[1][safety_devices]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_sd_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Spillage Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->spillage_test) && $gsra1->spillage_test == 'Pass' ? '1' : '0') }}" id="app_1_spgt_yes" name="app[1][spillage_test]" type="radio" value="Pass"/>
                                        <x-base.form-check.label for="app_1_spgt_yes">Pass</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->spillage_test) && $gsra1->spillage_test == 'Fail' ? '1' : '0') }}" id="app_1_spgt_no" name="app[1][spillage_test]" type="radio" value="Fail"/>
                                        <x-base.form-check.label for="app_1_spgt_no">Fail</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->spillage_test) && $gsra1->spillage_test == 'N/A' ? '1' : '0') }}" id="app_1_spgt_na" name="app[1][spillage_test]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_spgt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Smoke Pellet Flue Flow Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->smoke_pellet_test) && $gsra1->smoke_pellet_test == 'Pass' ? '1' : '0') }}" id="app_1_spfft_yes" name="app[1][smoke_pellet_test]" type="radio" value="Pass"/>
                                        <x-base.form-check.label for="app_1_spfft_yes">Pass</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->smoke_pellet_test) && $gsra1->smoke_pellet_test == 'Fail' ? '1' : '0') }}" id="app_1_spfft_no" name="app[1][smoke_pellet_test]" type="radio" value="Fail"/>
                                        <x-base.form-check.label for="app_1_spfft_no">Fail</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->smoke_pellet_test) && $gsra1->smoke_pellet_test == 'Pass' ? '1' : '0') }}" id="app_1_spfft_na" name="app[1][smoke_pellet_test]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_spfft_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
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
                                <x-base.form-label>Satisfactory Termination</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->satisfactory_termination) && $gsra1->satisfactory_termination == 'Yes' ? '1' : '0') }}" id="app_1_sft_yes" name="app[1][satisfactory_termination]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_sft_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->satisfactory_termination) && $gsra1->satisfactory_termination == 'No' ? '1' : '0') }}" id="app_1_sft_no" name="app[1][satisfactory_termination]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_sft_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->satisfactory_termination) && $gsra1->satisfactory_termination == 'N/A' ? '1' : '0') }}" id="app_1_sft_na" name="app[1][satisfactory_termination]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_sft_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Visual Condition</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->flue_visual_condition) && $gsra1->flue_visual_condition == 'Yes' ? '1' : '0') }}" id="app_1_fvc_yes" name="app[1][flue_visual_condition]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_fvc_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->flue_visual_condition) && $gsra1->flue_visual_condition == 'No' ? '1' : '0') }}" id="app_1_fvc_no" name="app[1][flue_visual_condition]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_fvc_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->flue_visual_condition) && $gsra1->flue_visual_condition == 'N/A' ? '1' : '0') }}" id="app_1_fvc_na" name="app[1][flue_visual_condition]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_fvc_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Adequate Ventilation</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->adequate_ventilation) && $gsra1->adequate_ventilation == 'Yes' ? '1' : '0') }}" id="app_1_av_yes" name="app[1][adequate_ventilation]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_av_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->adequate_ventilation) && $gsra1->adequate_ventilation == 'No' ? '1' : '0') }}" id="app_1_av_no" name="app[1][adequate_ventilation]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_av_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->adequate_ventilation) && $gsra1->adequate_ventilation == 'N/A' ? '1' : '0') }}" id="app_1_av_na" name="app[1][adequate_ventilation]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_av_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Landlord's Appliance</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->landlord_appliance) && $gsra1->landlord_appliance == 'Yes' ? '1' : '0') }}" id="app_1_la_yes" name="app[1][landlord_appliance]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_la_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->landlord_appliance) && $gsra1->landlord_appliance == 'No' ? '1' : '0') }}" id="app_1_la_no" name="app[1][landlord_appliance]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_la_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->landlord_appliance) && $gsra1->landlord_appliance == 'N/A' ? '1' : '0') }}" id="app_1_la_na" name="app[1][landlord_appliance]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_la_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Inspected</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->inspected) && $gsra1->inspected == 'Yes' ? '1' : '0') }}" id="app_1_ipt_yes" name="app[1][inspected]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_ipt_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->inspected) && $gsra1->inspected == 'No' ? '1' : '0') }}" id="app_1_ipt_no" name="app[1][inspected]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_ipt_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->inspected) && $gsra1->inspected == 'N/A' ? '1' : '0') }}" id="app_1_ipt_na" name="app[1][inspected]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_ipt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Visual Check</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_visual_check) && $gsra1->appliance_visual_check == 'Yes' ? '1' : '0') }}" id="app_1_avc_yes" name="app[1][appliance_visual_check]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_avc_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_visual_check) && $gsra1->appliance_visual_check == 'No' ? '1' : '0') }}" id="app_1_avc_no" name="app[1][appliance_visual_check]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_avc_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_visual_check) && $gsra1->appliance_visual_check == 'N/A' ? '1' : '0') }}" id="app_1_avc_na" name="app[1][appliance_visual_check]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_avc_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Serviced</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_serviced) && $gsra1->appliance_serviced == 'Yes' ? '1' : '0') }}" id="app_1_as_yes" name="app[1][appliance_serviced]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_as_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_serviced) && $gsra1->appliance_serviced == 'No' ? '1' : '0') }}" id="app_1_as_no" name="app[1][appliance_serviced]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_as_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_serviced) && $gsra1->appliance_serviced == 'N/A' ? '1' : '0') }}" id="app_1_as_na" name="app[1][appliance_serviced]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_as_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Safe to Use</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_safe_to_use) && $gsra1->appliance_safe_to_use == 'Yes' ? '1' : '0') }}" id="app_1_asu_yes" name="app[1][appliance_safe_to_use]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_1_asu_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_safe_to_use) && $gsra1->appliance_safe_to_use == 'No' ? '1' : '0') }}" id="app_1_asu_no" name="app[1][appliance_safe_to_use]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_1_asu_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra1->appliance_safe_to_use) && $gsra1->appliance_safe_to_use == 'N/A' ? '1' : '0') }}" id="app_1_asu_na" name="app[1][appliance_safe_to_use]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_1_asu_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
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
                        <h2 class="text-base font-medium">Appliance 2 <span class="info">{{ (isset($gsra2->make->name) && !empty($gsra2->make->name) ? $gsra2->make->name.' ' : '') }}{{ (isset($gsra2->type->name) && !empty($gsra2->type->name) ? $gsra2->type->name.' ' : '') }}</h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="applianceDetailsForm2">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <input type="hidden" name="appliance_serial" value="2"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Location</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[2][appliance_location_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($locations->count() > 0)
                                        @foreach($locations as $option)
                                            <option {{ (isset($gsra2->appliance_location_id) && $gsra2->appliance_location_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Make</x-base.form-label>
                                <x-base.tom-select class="w-full applianceMake" name="app[2][boiler_brand_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($boilers->count() > 0)
                                        @foreach($boilers as $option)
                                            <option {{ (isset($gsra2->boiler_brand_id) && $gsra2->boiler_brand_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Model</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra2->model) ? $gsra2->model : '') }}" name="app[2][model]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Model" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type</x-base.form-label>
                                <x-base.tom-select class="w-full applianceType" name="app[2][appliance_type_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($types->count() > 0)
                                        @foreach($types as $option)
                                            <option {{ (isset($gsra2->appliance_type_id) && $gsra2->appliance_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Serial Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra2->serial_no) ? $gsra2->serial_no : '') }}" name="app[2][serial_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>GC Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra2->gc_no) ? $gsra2->gc_no : '') }}" name="app[2][gc_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="GC Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Type</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[2][appliance_flue_type_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($flue_types->count() > 0)
                                        @foreach($flue_types as $option)
                                            <option {{ (isset($gsra2->appliance_flue_type_id) && $gsra2->appliance_flue_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating Pressure (mbar) or Heat Input (KW/h) or (BTU/h)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra2->opt_pressure) ? $gsra2->opt_pressure : '') }}" name="app[2][opt_pressure]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Operating Pressure" />
                            </div>
                            <div class="col-span-12 sm:col-span-4"></div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Safety Devices</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->safety_devices) && $gsra2->safety_devices == 'Yes' ? '1' : '0') }}" id="app_2_sd_yes" name="app[2][safety_devices]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_sd_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->safety_devices) && $gsra2->safety_devices == 'No' ? '1' : '0') }}" id="app_2_sd_no" name="app[2][safety_devices]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_sd_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->safety_devices) && $gsra2->safety_devices == 'N/A' ? '1' : '0') }}" id="app_2_sd_na" name="app[2][safety_devices]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_sd_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Spillage Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->spillage_test) && $gsra2->spillage_test == 'Pass' ? '1' : '0') }}" id="app_2_spgt_yes" name="app[2][spillage_test]" type="radio" value="Pass"/>
                                        <x-base.form-check.label for="app_2_spgt_yes">Pass</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->spillage_test) && $gsra2->spillage_test == 'Fail' ? '1' : '0') }}" id="app_2_spgt_no" name="app[2][spillage_test]" type="radio" value="Fail"/>
                                        <x-base.form-check.label for="app_2_spgt_no">Fail</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->spillage_test) && $gsra2->spillage_test == 'N/A' ? '1' : '0') }}" id="app_2_spgt_na" name="app[2][spillage_test]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_spgt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Smoke Pellet Flue Flow Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->smoke_pellet_test) && $gsra2->smoke_pellet_test == 'Pass' ? '1' : '0') }}" id="app_2_spfft_yes" name="app[2][smoke_pellet_test]" type="radio" value="Pass"/>
                                        <x-base.form-check.label for="app_2_spfft_yes">Pass</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->smoke_pellet_test) && $gsra2->smoke_pellet_test == 'Fail' ? '1' : '0') }}" id="app_2_spfft_no" name="app[2][smoke_pellet_test]" type="radio" value="Fail"/>
                                        <x-base.form-check.label for="app_2_spfft_no">Fail</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->smoke_pellet_test) && $gsra2->smoke_pellet_test == 'Pass' ? '1' : '0') }}" id="app_2_spfft_na" name="app[2][smoke_pellet_test]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_spfft_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 pt-2">
                                <x-base.form-label>Inital (low) Combustion Analyser Reading</x-base.form-label>
                                <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gsra2->low_analyser_ratio) ? $gsra2->low_analyser_ratio : '') }}" name="app[2][low_analyser_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                                <x-base.form-input value="{{ (isset($gsra2->low_co) ? $gsra2->low_co : '') }}" name="app[2][low_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                                <x-base.form-input value="{{ (isset($gsra2->low_co2) ? $gsra2->low_co2 : '') }}" name="app[2][low_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
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
                                                <x-base.form-input value="{{ (isset($gsra2->high_analyser_ratio) ? $gsra2->high_analyser_ratio : '') }}" name="app[2][high_analyser_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                                <x-base.form-input value="{{ (isset($gsra2->high_co) ? $gsra2->high_co : '') }}" name="app[2][high_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                                <x-base.form-input value="{{ (isset($gsra2->high_co2) ? $gsra2->high_co2 : '') }}" name="app[2][high_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Satisfactory Termination</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->satisfactory_termination) && $gsra2->satisfactory_termination == 'Yes' ? '1' : '0') }}" id="app_2_sft_yes" name="app[2][satisfactory_termination]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_sft_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->satisfactory_termination) && $gsra2->satisfactory_termination == 'No' ? '1' : '0') }}" id="app_2_sft_no" name="app[2][satisfactory_termination]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_sft_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->satisfactory_termination) && $gsra2->satisfactory_termination == 'N/A' ? '1' : '0') }}" id="app_2_sft_na" name="app[2][satisfactory_termination]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_sft_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Visual Condition</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->flue_visual_condition) && $gsra2->flue_visual_condition == 'Yes' ? '1' : '0') }}" id="app_2_fvc_yes" name="app[2][flue_visual_condition]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_fvc_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->flue_visual_condition) && $gsra2->flue_visual_condition == 'No' ? '1' : '0') }}" id="app_2_fvc_no" name="app[2][flue_visual_condition]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_fvc_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->flue_visual_condition) && $gsra2->flue_visual_condition == 'N/A' ? '1' : '0') }}" id="app_2_fvc_na" name="app[2][flue_visual_condition]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_fvc_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Adequate Ventilation</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->adequate_ventilation) && $gsra2->adequate_ventilation == 'Yes' ? '1' : '0') }}" id="app_2_av_yes" name="app[2][adequate_ventilation]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_av_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->adequate_ventilation) && $gsra2->adequate_ventilation == 'No' ? '1' : '0') }}" id="app_2_av_no" name="app[2][adequate_ventilation]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_av_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->adequate_ventilation) && $gsra2->adequate_ventilation == 'N/A' ? '1' : '0') }}" id="app_2_av_na" name="app[2][adequate_ventilation]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_av_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Landlord's Appliance</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->landlord_appliance) && $gsra2->landlord_appliance == 'Yes' ? '1' : '0') }}" id="app_2_la_yes" name="app[2][landlord_appliance]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_la_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->landlord_appliance) && $gsra2->landlord_appliance == 'No' ? '1' : '0') }}" id="app_2_la_no" name="app[2][landlord_appliance]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_la_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->landlord_appliance) && $gsra2->landlord_appliance == 'N/A' ? '1' : '0') }}" id="app_2_la_na" name="app[2][landlord_appliance]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_la_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Inspected</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->inspected) && $gsra2->inspected == 'Yes' ? '1' : '0') }}" id="app_2_ipt_yes" name="app[2][inspected]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_ipt_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->inspected) && $gsra2->inspected == 'No' ? '1' : '0') }}" id="app_2_ipt_no" name="app[2][inspected]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_ipt_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->inspected) && $gsra2->inspected == 'N/A' ? '1' : '0') }}" id="app_2_ipt_na" name="app[2][inspected]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_ipt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Visual Check</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_visual_check) && $gsra2->appliance_visual_check == 'Yes' ? '1' : '0') }}" id="app_2_avc_yes" name="app[2][appliance_visual_check]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_avc_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_visual_check) && $gsra2->appliance_visual_check == 'No' ? '1' : '0') }}" id="app_2_avc_no" name="app[2][appliance_visual_check]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_avc_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_visual_check) && $gsra2->appliance_visual_check == 'N/A' ? '1' : '0') }}" id="app_2_avc_na" name="app[2][appliance_visual_check]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_avc_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Serviced</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_serviced) && $gsra2->appliance_serviced == 'Yes' ? '1' : '0') }}" id="app_2_as_yes" name="app[2][appliance_serviced]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_as_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_serviced) && $gsra2->appliance_serviced == 'No' ? '1' : '0') }}" id="app_2_as_no" name="app[2][appliance_serviced]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_as_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_serviced) && $gsra2->appliance_serviced == 'N/A' ? '1' : '0') }}" id="app_2_as_na" name="app[2][appliance_serviced]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_as_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Safe to Use</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_safe_to_use) && $gsra2->appliance_safe_to_use == 'Yes' ? '1' : '0') }}" id="app_2_asu_yes" name="app[2][appliance_safe_to_use]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_2_asu_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_safe_to_use) && $gsra2->appliance_safe_to_use == 'No' ? '1' : '0') }}" id="app_2_asu_no" name="app[2][appliance_safe_to_use]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_2_asu_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra2->appliance_safe_to_use) && $gsra2->appliance_safe_to_use == 'N/A' ? '1' : '0') }}" id="app_2_asu_na" name="app[2][appliance_safe_to_use]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_2_asu_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="2" class="form-wizard-previous-btn" variant="secondary">
                                <x-base.lucide class="h-5 w-5 mr-2" icon="move-left"/>Previous
                            </x-base.button>
                            <x-base.button type="button" data-appliance="2" class="form-wizard-next-btn ml-auto" variant="linkedin" >
                                Save & Continue<x-base.lucide class="theIcon h-5 w-5 ml-2" icon="move-right"/>
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        </div>
                    </form>
                </fieldset>
                <fieldset id="step_5" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium">Appliance 3 <span class="info">{{ (isset($gsra3->make->name) && !empty($gsra3->make->name) ? $gsra3->make->name.' ' : '') }}{{ (isset($gsra3->type->name) && !empty($gsra3->type->name) ? $gsra3->type->name.' ' : '') }}</h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="applianceDetailsForm3">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <input type="hidden" name="appliance_serial" value="3"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Location</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[3][appliance_location_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($locations->count() > 0)
                                        @foreach($locations as $option)
                                            <option {{ (isset($gsra3->appliance_location_id) && $gsra3->appliance_location_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Make</x-base.form-label>
                                <x-base.tom-select class="w-full applianceMake" name="app[3][boiler_brand_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($boilers->count() > 0)
                                        @foreach($boilers as $option)
                                            <option {{ (isset($gsra3->boiler_brand_id) && $gsra3->boiler_brand_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Model</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra3->model) ? $gsra3->model : '') }}" name="app[3][model]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Model" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type</x-base.form-label>
                                <x-base.tom-select class="w-full applianceType" name="app[3][appliance_type_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($types->count() > 0)
                                        @foreach($types as $option)
                                            <option {{ (isset($gsra3->appliance_type_id) && $gsra3->appliance_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Serial Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra3->serial_no) ? $gsra3->serial_no : '') }}" name="app[3][serial_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>GC Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra3->gc_no) ? $gsra3->gc_no : '') }}" name="app[3][gc_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="GC Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Type</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[3][appliance_flue_type_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($flue_types->count() > 0)
                                        @foreach($flue_types as $option)
                                            <option {{ (isset($gsra3->appliance_flue_type_id) && $gsra3->appliance_flue_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating Pressure (mbar) or Heat Input (KW/h) or (BTU/h)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra3->opt_pressure) ? $gsra3->opt_pressure : '') }}" name="app[3][opt_pressure]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Operating Pressure" />
                            </div>
                            <div class="col-span-12 sm:col-span-4"></div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Safety Devices</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->safety_devices) && $gsra3->safety_devices == 'Yes' ? '1' : '0') }}" id="app_3_sd_yes" name="app[3][safety_devices]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_sd_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->safety_devices) && $gsra3->safety_devices == 'No' ? '1' : '0') }}" id="app_3_sd_no" name="app[3][safety_devices]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_sd_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->safety_devices) && $gsra3->safety_devices == 'N/A' ? '1' : '0') }}" id="app_3_sd_na" name="app[3][safety_devices]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_sd_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Spillage Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->spillage_test) && $gsra3->spillage_test == 'Pass' ? '1' : '0') }}" id="app_3_spgt_yes" name="app[3][spillage_test]" type="radio" value="Pass"/>
                                        <x-base.form-check.label for="app_3_spgt_yes">Pass</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->spillage_test) && $gsra3->spillage_test == 'Fail' ? '1' : '0') }}" id="app_3_spgt_no" name="app[3][spillage_test]" type="radio" value="Fail"/>
                                        <x-base.form-check.label for="app_3_spgt_no">Fail</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->spillage_test) && $gsra3->spillage_test == 'N/A' ? '1' : '0') }}" id="app_3_spgt_na" name="app[3][spillage_test]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_spgt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Smoke Pellet Flue Flow Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->smoke_pellet_test) && $gsra3->smoke_pellet_test == 'Pass' ? '1' : '0') }}" id="app_3_spfft_yes" name="app[3][smoke_pellet_test]" type="radio" value="Pass"/>
                                        <x-base.form-check.label for="app_3_spfft_yes">Pass</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->smoke_pellet_test) && $gsra3->smoke_pellet_test == 'Fail' ? '1' : '0') }}" id="app_3_spfft_no" name="app[3][smoke_pellet_test]" type="radio" value="Fail"/>
                                        <x-base.form-check.label for="app_3_spfft_no">Fail</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->smoke_pellet_test) && $gsra3->smoke_pellet_test == 'Pass' ? '1' : '0') }}" id="app_3_spfft_na" name="app[3][smoke_pellet_test]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_spfft_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 pt-2">
                                <x-base.form-label>Inital (low) Combustion Analyser Reading</x-base.form-label>
                                <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gsra3->low_analyser_ratio) ? $gsra3->low_analyser_ratio : '') }}" name="app[3][low_analyser_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                                <x-base.form-input value="{{ (isset($gsra3->low_co) ? $gsra3->low_co : '') }}" name="app[3][low_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                                <x-base.form-input value="{{ (isset($gsra3->low_co2) ? $gsra3->low_co2 : '') }}" name="app[3][low_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
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
                                                <x-base.form-input value="{{ (isset($gsra3->high_analyser_ratio) ? $gsra3->high_analyser_ratio : '') }}" name="app[3][high_analyser_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                                <x-base.form-input value="{{ (isset($gsra3->high_co) ? $gsra3->high_co : '') }}" name="app[3][high_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                                <x-base.form-input value="{{ (isset($gsra3->high_co2) ? $gsra3->high_co2 : '') }}" name="app[3][high_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Satisfactory Termination</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->satisfactory_termination) && $gsra3->satisfactory_termination == 'Yes' ? '1' : '0') }}" id="app_3_sft_yes" name="app[3][satisfactory_termination]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_sft_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->satisfactory_termination) && $gsra3->satisfactory_termination == 'No' ? '1' : '0') }}" id="app_3_sft_no" name="app[3][satisfactory_termination]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_sft_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->satisfactory_termination) && $gsra3->satisfactory_termination == 'N/A' ? '1' : '0') }}" id="app_3_sft_na" name="app[3][satisfactory_termination]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_sft_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Visual Condition</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->flue_visual_condition) && $gsra3->flue_visual_condition == 'Yes' ? '1' : '0') }}" id="app_3_fvc_yes" name="app[3][flue_visual_condition]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_fvc_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->flue_visual_condition) && $gsra3->flue_visual_condition == 'No' ? '1' : '0') }}" id="app_3_fvc_no" name="app[3][flue_visual_condition]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_fvc_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->flue_visual_condition) && $gsra3->flue_visual_condition == 'N/A' ? '1' : '0') }}" id="app_3_fvc_na" name="app[3][flue_visual_condition]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_fvc_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Adequate Ventilation</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->adequate_ventilation) && $gsra3->adequate_ventilation == 'Yes' ? '1' : '0') }}" id="app_3_av_yes" name="app[3][adequate_ventilation]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_av_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->adequate_ventilation) && $gsra3->adequate_ventilation == 'No' ? '1' : '0') }}" id="app_3_av_no" name="app[3][adequate_ventilation]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_av_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->adequate_ventilation) && $gsra3->adequate_ventilation == 'N/A' ? '1' : '0') }}" id="app_3_av_na" name="app[3][adequate_ventilation]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_av_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Landlord's Appliance</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->landlord_appliance) && $gsra3->landlord_appliance == 'Yes' ? '1' : '0') }}" id="app_3_la_yes" name="app[3][landlord_appliance]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_la_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->landlord_appliance) && $gsra3->landlord_appliance == 'No' ? '1' : '0') }}" id="app_3_la_no" name="app[3][landlord_appliance]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_la_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->landlord_appliance) && $gsra3->landlord_appliance == 'N/A' ? '1' : '0') }}" id="app_3_la_na" name="app[3][landlord_appliance]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_la_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Inspected</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->inspected) && $gsra3->inspected == 'Yes' ? '1' : '0') }}" id="app_3_ipt_yes" name="app[3][inspected]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_ipt_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->inspected) && $gsra3->inspected == 'No' ? '1' : '0') }}" id="app_3_ipt_no" name="app[3][inspected]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_ipt_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->inspected) && $gsra3->inspected == 'N/A' ? '1' : '0') }}" id="app_3_ipt_na" name="app[3][inspected]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_ipt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Visual Check</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_visual_check) && $gsra3->appliance_visual_check == 'Yes' ? '1' : '0') }}" id="app_3_avc_yes" name="app[3][appliance_visual_check]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_avc_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_visual_check) && $gsra3->appliance_visual_check == 'No' ? '1' : '0') }}" id="app_3_avc_no" name="app[3][appliance_visual_check]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_avc_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_visual_check) && $gsra3->appliance_visual_check == 'N/A' ? '1' : '0') }}" id="app_3_avc_na" name="app[3][appliance_visual_check]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_avc_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Serviced</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_serviced) && $gsra3->appliance_serviced == 'Yes' ? '1' : '0') }}" id="app_3_as_yes" name="app[3][appliance_serviced]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_as_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_serviced) && $gsra3->appliance_serviced == 'No' ? '1' : '0') }}" id="app_3_as_no" name="app[3][appliance_serviced]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_as_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_serviced) && $gsra3->appliance_serviced == 'N/A' ? '1' : '0') }}" id="app_3_as_na" name="app[3][appliance_serviced]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_as_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Safe to Use</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_safe_to_use) && $gsra3->appliance_safe_to_use == 'Yes' ? '1' : '0') }}" id="app_3_asu_yes" name="app[3][appliance_safe_to_use]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_3_asu_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_safe_to_use) && $gsra3->appliance_safe_to_use == 'No' ? '1' : '0') }}" id="app_3_asu_no" name="app[3][appliance_safe_to_use]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_3_asu_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra3->appliance_safe_to_use) && $gsra3->appliance_safe_to_use == 'N/A' ? '1' : '0') }}" id="app_3_asu_na" name="app[3][appliance_safe_to_use]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_3_asu_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="3" class="form-wizard-previous-btn" variant="secondary">
                                <x-base.lucide class="h-5 w-5 mr-2" icon="move-left"/>Previous
                            </x-base.button>
                            <x-base.button type="button" data-appliance="3" class="form-wizard-next-btn ml-auto" variant="linkedin" >
                                Save & Continue<x-base.lucide class="theIcon h-5 w-5 ml-2" icon="move-right"/>
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        </div>
                    </form>
                </fieldset>
                <fieldset id="step_6" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium">Appliance 4 <span class="info">{{ (isset($gsra4->make->name) && !empty($gsra4->make->name) ? $gsra4->make->name.' ' : '') }}{{ (isset($gsra4->type->name) && !empty($gsra4->type->name) ? $gsra4->type->name.' ' : '') }}</h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="applianceDetailsForm4">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <input type="hidden" name="appliance_serial" value="4"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Location</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[4][appliance_location_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($locations->count() > 0)
                                        @foreach($locations as $option)
                                            <option {{ (isset($gsra4->appliance_location_id) && $gsra4->appliance_location_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Make</x-base.form-label>
                                <x-base.tom-select class="w-full applianceMake" name="app[4][boiler_brand_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($boilers->count() > 0)
                                        @foreach($boilers as $option)
                                            <option {{ (isset($gsra4->boiler_brand_id) && $gsra4->boiler_brand_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Model</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra4->model) ? $gsra4->model : '') }}" name="app[4][model]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Model" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Type</x-base.form-label>
                                <x-base.tom-select class="w-full applianceType" name="app[4][appliance_type_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($types->count() > 0)
                                        @foreach($types as $option)
                                            <option {{ (isset($gsra4->appliance_type_id) && $gsra4->appliance_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Serial Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra4->serial_no) ? $gsra4->serial_no : '') }}" name="app[4][serial_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>GC Number</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra4->gc_no) ? $gsra4->gc_no : '') }}" name="app[4][gc_no]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="GC Number" />
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Type</x-base.form-label>
                                <x-base.tom-select class="w-full" name="app[4][appliance_flue_type_id]" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($flue_types->count() > 0)
                                        @foreach($flue_types as $option)
                                            <option {{ (isset($gsra4->appliance_flue_type_id) && $gsra4->appliance_flue_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Operating Pressure (mbar) or Heat Input (KW/h) or (BTU/h)</x-base.form-label>
                                <x-base.form-input value="{{ (isset($gsra4->opt_pressure) ? $gsra4->opt_pressure : '') }}" name="app[4][opt_pressure]" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Operating Pressure" />
                            </div>
                            <div class="col-span-12 sm:col-span-4"></div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Safety Devices</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->safety_devices) && $gsra4->safety_devices == 'Yes' ? '1' : '0') }}" id="app_4_sd_yes" name="app[4][safety_devices]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_sd_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->safety_devices) && $gsra4->safety_devices == 'No' ? '1' : '0') }}" id="app_4_sd_no" name="app[4][safety_devices]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_sd_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->safety_devices) && $gsra4->safety_devices == 'N/A' ? '1' : '0') }}" id="app_4_sd_na" name="app[4][safety_devices]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_sd_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Spillage Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->spillage_test) && $gsra4->spillage_test == 'Pass' ? '1' : '0') }}" id="app_4_spgt_yes" name="app[4][spillage_test]" type="radio" value="Pass"/>
                                        <x-base.form-check.label for="app_4_spgt_yes">Pass</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->spillage_test) && $gsra4->spillage_test == 'Fail' ? '1' : '0') }}" id="app_4_spgt_no" name="app[4][spillage_test]" type="radio" value="Fail"/>
                                        <x-base.form-check.label for="app_4_spgt_no">Fail</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->spillage_test) && $gsra4->spillage_test == 'N/A' ? '1' : '0') }}" id="app_4_spgt_na" name="app[4][spillage_test]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_spgt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Smoke Pellet Flue Flow Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->smoke_pellet_test) && $gsra4->smoke_pellet_test == 'Pass' ? '1' : '0') }}" id="app_4_spfft_yes" name="app[4][smoke_pellet_test]" type="radio" value="Pass"/>
                                        <x-base.form-check.label for="app_4_spfft_yes">Pass</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->smoke_pellet_test) && $gsra4->smoke_pellet_test == 'Fail' ? '1' : '0') }}" id="app_4_spfft_no" name="app[4][smoke_pellet_test]" type="radio" value="Fail"/>
                                        <x-base.form-check.label for="app_4_spfft_no">Fail</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->smoke_pellet_test) && $gsra4->smoke_pellet_test == 'Pass' ? '1' : '0') }}" id="app_4_spfft_na" name="app[4][smoke_pellet_test]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_spfft_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 pt-2">
                                <x-base.form-label>Inital (low) Combustion Analyser Reading</x-base.form-label>
                                <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                                    <div class="col-span-12 sm:col-span-4">
                                        <div class="readings">
                                            <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                            <div class="block">
                                                <x-base.form-input value="{{ (isset($gsra4->low_analyser_ratio) ? $gsra4->low_analyser_ratio : '') }}" name="app[4][low_analyser_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                                <x-base.form-input value="{{ (isset($gsra4->low_co) ? $gsra4->low_co : '') }}" name="app[4][low_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                                <x-base.form-input value="{{ (isset($gsra4->low_co2) ? $gsra4->low_co2 : '') }}" name="app[4][low_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
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
                                                <x-base.form-input value="{{ (isset($gsra4->high_analyser_ratio) ? $gsra4->high_analyser_ratio : '') }}" name="app[4][high_analyser_ratio]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                                <x-base.form-input value="{{ (isset($gsra4->high_co) ? $gsra4->high_co : '') }}" name="app[4][high_co]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                                <x-base.form-input value="{{ (isset($gsra4->high_co2) ? $gsra4->high_co2 : '') }}" name="app[4][high_co2]" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
                                                <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                                    N/A
                                                </x-base.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Satisfactory Termination</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->satisfactory_termination) && $gsra4->satisfactory_termination == 'Yes' ? '1' : '0') }}" id="app_4_sft_yes" name="app[4][satisfactory_termination]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_sft_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->satisfactory_termination) && $gsra4->satisfactory_termination == 'No' ? '1' : '0') }}" id="app_4_sft_no" name="app[4][satisfactory_termination]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_sft_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->satisfactory_termination) && $gsra4->satisfactory_termination == 'N/A' ? '1' : '0') }}" id="app_4_sft_na" name="app[4][satisfactory_termination]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_sft_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Flue Visual Condition</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->flue_visual_condition) && $gsra4->flue_visual_condition == 'Yes' ? '1' : '0') }}" id="app_4_fvc_yes" name="app[4][flue_visual_condition]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_fvc_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->flue_visual_condition) && $gsra4->flue_visual_condition == 'No' ? '1' : '0') }}" id="app_4_fvc_no" name="app[4][flue_visual_condition]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_fvc_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->flue_visual_condition) && $gsra4->flue_visual_condition == 'N/A' ? '1' : '0') }}" id="app_4_fvc_na" name="app[4][flue_visual_condition]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_fvc_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Adequate Ventilation</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->adequate_ventilation) && $gsra4->adequate_ventilation == 'Yes' ? '1' : '0') }}" id="app_4_av_yes" name="app[4][adequate_ventilation]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_av_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->adequate_ventilation) && $gsra4->adequate_ventilation == 'No' ? '1' : '0') }}" id="app_4_av_no" name="app[4][adequate_ventilation]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_av_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->adequate_ventilation) && $gsra4->adequate_ventilation == 'N/A' ? '1' : '0') }}" id="app_4_av_na" name="app[4][adequate_ventilation]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_av_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Landlord's Appliance</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->landlord_appliance) && $gsra4->landlord_appliance == 'Yes' ? '1' : '0') }}" id="app_4_la_yes" name="app[4][landlord_appliance]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_la_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->landlord_appliance) && $gsra4->landlord_appliance == 'No' ? '1' : '0') }}" id="app_4_la_no" name="app[4][landlord_appliance]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_la_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->landlord_appliance) && $gsra4->landlord_appliance == 'N/A' ? '1' : '0') }}" id="app_4_la_na" name="app[4][landlord_appliance]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_la_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Inspected</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->inspected) && $gsra4->inspected == 'Yes' ? '1' : '0') }}" id="app_4_ipt_yes" name="app[4][inspected]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_ipt_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->inspected) && $gsra4->inspected == 'No' ? '1' : '0') }}" id="app_4_ipt_no" name="app[4][inspected]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_ipt_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->inspected) && $gsra4->inspected == 'N/A' ? '1' : '0') }}" id="app_4_ipt_na" name="app[4][inspected]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_ipt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Visual Check</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_visual_check) && $gsra4->appliance_visual_check == 'Yes' ? '1' : '0') }}" id="app_4_avc_yes" name="app[4][appliance_visual_check]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_avc_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_visual_check) && $gsra4->appliance_visual_check == 'No' ? '1' : '0') }}" id="app_4_avc_no" name="app[4][appliance_visual_check]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_avc_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_visual_check) && $gsra4->appliance_visual_check == 'N/A' ? '1' : '0') }}" id="app_4_avc_na" name="app[4][appliance_visual_check]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_avc_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Serviced</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_serviced) && $gsra4->appliance_serviced == 'Yes' ? '1' : '0') }}" id="app_4_as_yes" name="app[4][appliance_serviced]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_as_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_serviced) && $gsra4->appliance_serviced == 'No' ? '1' : '0') }}" id="app_4_as_no" name="app[4][appliance_serviced]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_as_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_serviced) && $gsra4->appliance_serviced == 'N/A' ? '1' : '0') }}" id="app_4_as_na" name="app[4][appliance_serviced]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_as_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Appliance Safe to Use</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_safe_to_use) && $gsra4->appliance_safe_to_use == 'Yes' ? '1' : '0') }}" id="app_4_asu_yes" name="app[4][appliance_safe_to_use]" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_asu_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_safe_to_use) && $gsra4->appliance_safe_to_use == 'No' ? '1' : '0') }}" id="app_4_asu_no" name="app[4][appliance_safe_to_use]" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_asu_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsra4->appliance_safe_to_use) && $gsra4->appliance_safe_to_use == 'N/A' ? '1' : '0') }}" id="app_4_asu_na" name="app[4][appliance_safe_to_use]" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_asu_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-5 flex items-center justify-between">
                            <x-base.button type="button" data-appliance="4" class="form-wizard-previous-btn" variant="secondary">
                                <x-base.lucide class="h-5 w-5 mr-2" icon="move-left"/>Previous
                            </x-base.button>
                            <x-base.button type="button" data-appliance="4" class="form-wizard-next-btn ml-auto" variant="linkedin" >
                                Save & Continue<x-base.lucide class="theIcon h-5 w-5 ml-2" icon="move-right"/>
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                            </x-base.button>
                        </div>
                    </form>
                </fieldset>
                <fieldset id="step_7" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium">CP Alarm(s)</h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="coAlarmsForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12">
                                <x-base.form-switch>
                                    <x-base.form-switch.label for="cp_alarm_fitted" class="ml-0 font-medium">
                                        CP Alarm(s) Fitted
                                    </x-base.form-switch.label>
                                    <x-base.form-switch.input checked="{{ (isset($gsr->cp_alarm_fitted) && $gsr->cp_alarm_fitted == 1 ? '1' : '0') }}" id="cp_alarm_fitted" name="cp_alarm_fitted" value="1" class="ml-5 mr-0" type="checkbox" />
                                </x-base.form-switch>
                            </div>
                            <div class="col-span-12">
                                <x-base.form-switch>
                                    <x-base.form-switch.label for="cp_alarm_satisfactory" class="ml-0 font-medium">
                                        CP Alarm(s) Tested & Satisfactory
                                    </x-base.form-switch.label>
                                    <x-base.form-switch.input checked="{{ (isset($gsr->cp_alarm_satisfactory) && $gsr->cp_alarm_satisfactory == 1 ? '1' : '0') }}" id="cp_alarm_satisfactory" name="cp_alarm_satisfactory" value="1" class="ml-5 mr-0" type="checkbox" />
                                </x-base.form-switch>
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
                <fieldset id="step_8" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium">Safety Checks</h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="safetyCheckForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12">
                                <h3 class="font-bold text-base tracking-normal">Gas Installation Pipework</h3>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label>Satisfactory Visual Inspection</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->satisfactory_visual_inspaction) && $gsr->satisfactory_visual_inspaction == 'Yes' ? '1' : '0') }}" id="app_4_gip_svi_yes" name="satisfactory_visual_inspaction" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_gip_svi_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->satisfactory_visual_inspaction) && $gsr->satisfactory_visual_inspaction == 'No' ? '1' : '0') }}" id="app_4_gip_svi_no" name="satisfactory_visual_inspaction" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_gip_svi_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label>Emergency Control Accessible</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->emergency_control_accessible) && $gsr->emergency_control_accessible == 'Yes' ? '1' : '0') }}" id="app_4_gip_eca_yes" name="emergency_control_accessible" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_gip_eca_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->emergency_control_accessible) && $gsr->emergency_control_accessible == 'No' ? '1' : '0') }}" id="app_4_gip_eca_no" name="emergency_control_accessible" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_gip_eca_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label>Satisfactory Gas Tightness Test</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->satisfactory_gas_tightness_test) && $gsr->satisfactory_gas_tightness_test == 'Yes' ? '1' : '0') }}" id="app_4_gip_sgtt_yes" name="satisfactory_gas_tightness_test" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_gip_sgtt_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->satisfactory_gas_tightness_test) && $gsr->satisfactory_gas_tightness_test == 'No' ? '1' : '0') }}" id="app_4_gip_sgtt_no" name="satisfactory_gas_tightness_test" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_gip_sgtt_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->satisfactory_gas_tightness_test) && $gsr->satisfactory_gas_tightness_test == 'N/A' ? '1' : '0') }}" id="app_4_gip_sgtt_na" name="satisfactory_gas_tightness_test" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_gip_sgtt_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label>Equipotential Bonding Satisfactory</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->equipotential_bonding_satisfactory) && $gsr->equipotential_bonding_satisfactory == 'Yes' ? '1' : '0') }}" id="app_4_gip_ebs_yes" name="equipotential_bonding_satisfactory" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_gip_ebs_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->equipotential_bonding_satisfactory) && $gsr->equipotential_bonding_satisfactory == 'No' ? '1' : '0') }}" id="app_4_gip_ebs_no" name="equipotential_bonding_satisfactory" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_gip_ebs_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->equipotential_bonding_satisfactory) && $gsr->equipotential_bonding_satisfactory == 'N/A' ? '1' : '0') }}" id="app_4_gip_ebs_na" name="equipotential_bonding_satisfactory" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_gip_ebs_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 pt-3">
                                <h3 class="font-bold text-base tracking-normal">Audible CO Alarms</h3>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label>Approved CO Alarm Fitted</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_fitted) && $gsr->co_alarm_fitted == 'Yes' ? '1' : '0') }}" id="app_4_gip_acoaf_yes" name="co_alarm_fitted" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_gip_acoaf_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_fitted) && $gsr->co_alarm_fitted == 'No' ? '1' : '0') }}" id="app_4_gip_acoaf_no" name="co_alarm_fitted" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_gip_acoaf_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_fitted) && $gsr->co_alarm_fitted == 'N/A' ? '1' : '0') }}" id="app_4_gip_acoaf_na" name="co_alarm_fitted" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_gip_acoaf_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label>Are CO Alarm in Date</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_in_date) && $gsr->co_alarm_in_date == 'Yes' ? '1' : '0') }}" id="app_4_gip_acoid_yes" name="co_alarm_in_date" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_gip_acoid_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_in_date) && $gsr->co_alarm_in_date == 'No' ? '1' : '0') }}" id="app_4_gip_acoid_no" name="co_alarm_in_date" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_gip_acoid_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_in_date) && $gsr->co_alarm_in_date == 'N/A' ? '1' : '0') }}" id="app_4_gip_acoid_na" name="co_alarm_in_date" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_gip_acoid_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label>Testing of CO Alarm Satisfactory</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_test_satisfactory) && $gsr->co_alarm_test_satisfactory == 'Yes' ? '1' : '0') }}" id="app_4_gip_tcoas_yes" name="co_alarm_test_satisfactory" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_gip_tcoas_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_test_satisfactory) && $gsr->co_alarm_test_satisfactory == 'No' ? '1' : '0') }}" id="app_4_gip_tcoas_no" name="co_alarm_test_satisfactory" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_gip_tcoas_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->co_alarm_test_satisfactory) && $gsr->co_alarm_test_satisfactory == 'N/A' ? '1' : '0') }}" id="app_4_gip_tcoas_na" name="co_alarm_test_satisfactory" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_gip_tcoas_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <x-base.form-label>Smoke Alarms Fitted</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->smoke_alarm_fitted) && $gsr->smoke_alarm_fitted == 'Yes' ? '1' : '0') }}" id="app_4_gip_saf_yes" name="smoke_alarm_fitted" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_gip_saf_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->smoke_alarm_fitted) && $gsr->smoke_alarm_fitted == 'No' ? '1' : '0') }}" id="app_4_gip_saf_no" name="smoke_alarm_fitted" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_gip_saf_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->smoke_alarm_fitted) && $gsr->smoke_alarm_fitted == 'N/A' ? '1' : '0') }}" id="app_4_gip_saf_na" name="smoke_alarm_fitted" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_gip_saf_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
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
                <fieldset id="step_9" class="wizard-fieldset intro-y box mb-3">
                    <div class="wizard-fieldset-header cursor-pointer flex items-center sm:hidden border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="text-base font-medium">Comments</h2>
                        <x-base.lucide class="w-4 h-4 ml-auto" icon="chevron-down"/>
                    </div>
                    <form method="post" action="#" class="wizard-step-form" enctype="multipart/form-data" id="commentsForm">
                        <input type="hidden" name="customer_job_id" value="{{ $job->id }}"/>
                        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3 px-5 pt-5">
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Give Any Details of Any Faults</x-base.form-label>
                                <x-base.form-textarea name="fault_details" class="w-full h-[95px] rounded-[3px]" placeholder="Give Any Details of Any Faults">{{ (isset($gsr->fault_details) && !empty($gsr->fault_details) ? $gsr->fault_details : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Rectification Work Carried Out</x-base.form-label>
                                <x-base.form-textarea name="rectification_work_carried_out" class="w-full h-[95px] rounded-[3px]" placeholder="Rectification Work Carried Out">{{ (isset($gsr->rectification_work_carried_out) && !empty($gsr->rectification_work_carried_out) ? $gsr->rectification_work_carried_out : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <x-base.form-label class="mb-1">Details of Works Carried Out</x-base.form-label>
                                <x-base.form-textarea name="details_work_carried_out" class="w-full h-[95px] rounded-[3px]" placeholder="Rectification Work Carried Out">{{ (isset($gsr->details_work_carried_out) && !empty($gsr->details_work_carried_out) ? $gsr->details_work_carried_out : '') }}</x-base.form-textarea>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <x-base.form-label>Has Flue Cap Been Put Back?</x-base.form-label>
                                <div class="flex flex-col sm:flex-row">
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->flue_cap_put_back) && $gsr->flue_cap_put_back == 'Yes' ? '1' : '0') }}" id="app_4_hfcbpb_yes" name="flue_cap_put_back" type="radio" value="Yes"/>
                                        <x-base.form-check.label for="app_4_hfcbpb_yes">Yes</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->flue_cap_put_back) && $gsr->flue_cap_put_back == 'No' ? '1' : '0') }}" id="app_4_hfcbpb_no" name="flue_cap_put_back" type="radio" value="No"/>
                                        <x-base.form-check.label for="app_4_hfcbpb_no">No</x-base.form-check.label>
                                    </x-base.form-check>
                                    <x-base.form-check class="mr-2">
                                        <x-base.form-check.input checked="{{ (isset($gsr->flue_cap_put_back) && $gsr->flue_cap_put_back == 'N/A' ? '1' : '0') }}" id="app_4_hfcbpb_na" name="flue_cap_put_back" type="radio" value="N/A"/>
                                        <x-base.form-check.label for="app_4_hfcbpb_na">N/A</x-base.form-check.label>
                                    </x-base.form-check>
                                </div>
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
                <fieldset id="step_10" class="wizard-fieldset intro-y box mb-3">
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
                                <x-base.form-label>Relation</x-base.form-label>
                                <x-base.tom-select class="w-full" name="relation_id" data-placeholder="Please Select">
                                    <option value="">Please Select</option>
                                    @if($relations->count() > 0)
                                        @foreach($relations as $option)
                                            <option {{ (isset($gsr->relation_id) && $gsr->relation_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    @endif
                                </x-base.tom-select>
                            </div>
                            <div class="col-span-12 sm:col-span-12">
                                <div class="border rounded-[3px] h-[350px] bg-slate-100 rounded-b-none"></div>
                                <x-base.button type="button" class="w-full flex justify-center items-center rounded-t-none" variant="secondary">
                                    Undo Signature
                                </x-base.button>
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
            </div>
        </div>
    </div>
    

    @include('app.records.homeowner_gas_safety_record.modals')
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
    @vite('resources/js/app/records/homewoner_gass_safety_record.js')
@endPushOnce