<!-- BEGIN: Appliance Modal Content -->
<x-base.dialog id="applianceModal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="applianceForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add Appliance</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <input type="hidden" name="appliance_serial" value="1"/>
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Location</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="appliance_location_id" name="appliance_location_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($locations->count() > 0)
                                @foreach($locations as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Make</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0 applianceMake" id="boiler_brand_id" name="boiler_brand_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($boilers->count() > 0)
                                @foreach($boilers as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Model</x-base.form-label>
                        <x-base.form-input value="" name="model" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Model" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Type</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0 applianceType" id="appliance_type_id" name="appliance_type_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($types->count() > 0)
                                @foreach($types as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Serial Number</x-base.form-label>
                        <x-base.form-input value="" name="serial_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">GC Number</x-base.form-label>
                        <x-base.form-input value="" name="gc_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="GC Number" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Classifications</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0 pl-0" id="gas_warning_classification_id" name="gas_warning_classification_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($classifications->count() > 0)
                                @foreach($classifications as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Gas Escape Issue</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_gei_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_gei_yes" name="gas_escape_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_gei_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_gei_no" name="gas_escape_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_gei_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_gei_na" name="gas_escape_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Pipework Issue</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ppw_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ppw_yes" name="pipework_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ppw_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ppw_no" name="pipework_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ppw_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ppw_na" name="pipework_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Ventilation Issue</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_vnt_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_vnt_yes" name="ventilation_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_vnt_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_vnt_no" name="ventilation_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_vnt_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_vnt_na" name="ventilation_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Meter Issue</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mtr_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mtr_yes" name="meter_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mtr_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mtr_no" name="meter_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mtr_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mtr_na" name="meter_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Chimney / Flue Issue</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_cmnf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_cmnf_yes" name="chimeny_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_cmnf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_cmnf_no" name="chimeny_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_cmnf_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_cmnf_na" name="chimeny_issue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Details of Faults</x-base.form-label>
                        <x-base.form-textarea name="fault_details" class="w-full h-[95px] rounded-[3px]" placeholder="Details of Faults"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Action Taken</x-base.form-label>
                        <x-base.form-textarea name="action_taken" class="w-full h-[95px] rounded-[3px]" placeholder="Action Taken"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Actions Required</x-base.form-label>
                        <x-base.form-textarea name="actions_required" class="w-full h-[95px] rounded-[3px]" placeholder="Actions Required"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6"></div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Reported to HSE under RIDDOR 11(1) (Gas Incident)</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rhse_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rhse_yes" name="reported_to_hse" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rhse_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rhse_no" name="reported_to_hse" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rhse_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rhse_na" name="reported_to_hse" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Reported to HDE under RIDDOR 1(2) (Dangerous Gas Fitting)</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rhde_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rhde_yes" name="reported_to_hde" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rhde_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rhde_no" name="reported_to_hde" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rhde_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rhde_na" name="reported_to_hde" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">
                                The gas user was not present at the time of this visit and where appropriate, (an IMMEDIATELY DENGEROUS (ID) or AT RISK 
                                (AR) solution) the installation has been made safe and this notice left on the premisies.
                            </x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_lops_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_lops_yes" name="left_on_premisies" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_lops_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_lops_no" name="left_on_premisies" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveApplianceBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Appliance Modal Content -->