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
                    <div class="col-span-12">
                        <h2 class="mb-4 font-medium text-base leading-none tracking-normal">Appliance Details</h2>
                    </div>
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
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Emission / Combustion test</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_emcmt_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_emcmt_yes" name="emition_combustion_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_emcmt_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_emcmt_no" name="emition_combustion_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_emcmt_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_emcmt_na" name="emition_combustion_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 pt-2 ratioWrap" style="display: none;">
                        <x-base.form-label>Combustion Parformance Reading (high)</x-base.form-label>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="performance_analyser_ratio" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="Ratio" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO (PPM)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="performance_co" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO (PPM)" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO<sub>2</sub> (%)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="performance_co2" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO2 (%)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-5 font-medium text-base leading-none tracking-normal">Safety standards</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance is operationg correctly</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aioc_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aioc_yes" name="opt_correctly" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aioc_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aioc_no" name="opt_correctly" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance confirms to current safety standards</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_actcss_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_actcss_yes" name="conf_safety_standards" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_actcss_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_actcss_no" name="conf_safety_standards" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 noticeExplainedWrap" style="display: none;">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Safety warning/advice notice explained and left</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_swaneal_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_swaneal_yes" name="notice_exlained" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_swaneal_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_swaneal_no" name="notice_exlained" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance flueing is safe</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aflis_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aflis_yes" name="flueing_is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aflis_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aflis_no" name="flueing_is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aflis_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aflis_na" name="flueing_is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance ventilation is safe</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_avis_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_avis_yes" name="ventilation_is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_avis_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_avis_no" name="ventilation_is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_avis_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_avis_na" name="ventilation_is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Emission/combustion test</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_avis_pass">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_avis_pass" name="emisn_or_comb_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_avis_fail">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_avis_fail" name="emisn_or_comb_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_avis_pfna">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_avis_pfna" name="emisn_or_comb_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Burner pressure / Gas rate correct</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_bpgrc_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_bpgrc_yes" name="burner_pressure" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_bpgrc_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_bpgrc_no" name="burner_pressure" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_bpgrc_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_bpgrc_na" name="burner_pressure" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Tightness Test carried out</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ttcot_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ttcot_yes" name="tightness_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ttcot_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ttcot_no" name="tightness_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ttcot_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ttcot_na" name="tightness_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>



                    <div class="col-span-12">
                        <h2 class="mb-4 mt-5 font-medium text-base leading-none tracking-normal">Breakdown</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Location of Fault</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_locofflt_yes">Appliance</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_locofflt_yes" name="location_of_fault" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Appliance"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_locofflt_no">System</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_locofflt_no" name="location_of_fault" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="System"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Fault resolved</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fltreslv_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fltreslv_yes" name="fault_resolved" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fltreslv_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fltreslv_no" name="fault_resolved" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4"></div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Parts fitted this visit</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_pftv_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_pftv_yes" name="parts_fitted" class="hasDetail absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_pftv_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_pftv_no" name="parts_fitted" class="hasDetail absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <div class="partsFittedWrap">
                            <x-base.form-label class="mb-1">If yes, Enter name of parts</x-base.form-label>
                            <x-base.form-textarea disabled name="parts_fitted_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Parts required</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_prtsreq_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_prtsreq_yes" name="parts_required" class="hasDetail absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_prtsreq_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_prtsreq_no" name="parts_required" class="hasDetail absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <div class="partsFittedWrap">
                            <x-base.form-label class="mb-1">If yes, Enter parts name</x-base.form-label>
                            <x-base.form-textarea disabled name="parts_required_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                        </div>
                    </div>


                    <div class="col-span-12">
                        <h2 class="mb-4 mt-5 font-medium text-base leading-none tracking-normal">Appliance / system advice and recommendations</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance is safe</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_issafe_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_issafe_yes" name="is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_issafe_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_issafe_no" name="is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_issafe_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_issafe_na" name="is_safe" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">System improvement(s) recommended</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_sysir_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_sysir_yes" name="improvement_recommended" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_sysir_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_sysir_no" name="improvement_recommended" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">All functional parts is available</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_afpia_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_afpia_yes" name="parts_available" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_afpia_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_afpia_no" name="parts_available" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Recommended appliance replacement</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rcarp_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rcarp_yes" name="recommend_replacement" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rcarp_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rcarp_no" name="recommend_replacement" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Magnetic system filter fitted</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mgsff_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mgsff_yes" name="magnetic_filter_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mgsff_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mgsff_no" name="magnetic_filter_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Approved audible carbon monoxide alarm fitted*</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aacmaf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aacmaf_yes" name="monoxide_alarm_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aacmaf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aacmaf_no" name="monoxide_alarm_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label class="mb-1">Engineer Comments</x-base.form-label>
                        <x-base.form-textarea name="enginner_comments" class="w-full h-[80px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                        <span class="text-xs leading-none">*Although regular servicing is essential, an audible Carbon Monoxide Alarm is an early warning device that keeps working 24 hours a day should an unforeseen event occur. It is recommended that customers check the expiry date periodically.</span>
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