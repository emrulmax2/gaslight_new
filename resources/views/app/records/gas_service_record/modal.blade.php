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
                        <x-base.form-label class="mb-1">Flue Type</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0 applianceflueType" id="appliance_flue_type_id" name="appliance_flue_type_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($flue_types->count() > 0)
                                @foreach($flue_types as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-5 font-medium text-base leading-none tracking-normal">Installation Details</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Rented Accommodation</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_racc_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_racc_yes" name="rented_accommodation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_racc_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_racc_no" name="rented_accommodation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Type of Work Carried Out</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_towco_yes">Service</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_towco_yes" name="type_of_work_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Service"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_towco_no">Maintenance</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_towco_no" name="type_of_work_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Maintenance"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_towco_na">Call Out</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_towco_na" name="type_of_work_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Call Out"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Has a gas tightness test been carried out?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_gstn_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_gstn_yes" name="gas_tightness" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_gstn_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_gstn_no" name="gas_tightness" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 tightnessTestResult" style="display: none;">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Was the tightness test is a Pass or Fail?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_tcro_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_tcro_yes" name="test_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_tcro_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_tcro_no" name="test_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is electrical bonding (where required) satisfactory?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iseb_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iseb_yes" name="is_electricial_bonding" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iseb_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iseb_no" name="is_electricial_bonding" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iseb_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iseb_na" name="is_electricial_bonding" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-5 font-medium text-base leading-none tracking-normal">Electronic combustion gas analyser (ECGA) readings</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Has a full strip and clean service been cared out</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hafsacsbco_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hafsacsbco_yes" name="full_strip_cared_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hafsacsbco_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hafsacsbco_no" name="full_strip_cared_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
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
                                        <x-base.form-input value="" name="low_analyser_ratio" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="Ratio" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO (PPM)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="low_co" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO (PPM)" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO<sub>2</sub> (%)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="low_co2" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO2 (%)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 pt-2 analyserRatioWraper" style="display: none;">
                        <x-base.form-label>Final (high) Combustion Analyser Reading</x-base.form-label>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="high_analyser_ratio" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="Ratio" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO (PPM)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="high_co" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO (PPM)" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO<sub>2</sub> (%)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="high_co2" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO2 (%)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-5 font-medium text-base leading-none tracking-normal">Safety - General</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Ventilation correct</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ventl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ventl_yes" name="ventillation" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ventl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ventl_no" name="ventillation" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="ventillation_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Satisfactory flue flow check</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_spfft_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_spfft_yes" name="satisfactory_flue_flow" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_spfft_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_spfft_no" name="satisfactory_flue_flow" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_spfft_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_spfft_na" name="satisfactory_flue_flow" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="satisfactory_flue_flow_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Satisfactory Flue termination</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stfFluTerm_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stfFluTerm_yes" name="satisfactory_flue_termination" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stfFluTerm_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stfFluTerm_no" name="satisfactory_flue_termination" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stfFluTerm_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stfFluTerm_na" name="satisfactory_flue_termination" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="satisfactory_flue_termination_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Satistactory spillage test</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_smst_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_smst_yes" name="satistactory_spillage" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_smst_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_smst_no" name="satistactory_spillage" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_smst_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_smst_na" name="satistactory_spillage" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="satistactory_spillage_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Safety device(s) correct</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_sftd_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_sftd_yes" name="savety_devices" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_sftd_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_sftd_no" name="savety_devices" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_sftd_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_sftd_na" name="savety_devices" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="savety_devices_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Pipework</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_pipw_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_pipw_yes" name="pipework" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_pipw_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_pipw_no" name="pipework" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_pipw_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_pipw_na" name="pipework" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="pipework_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Other (regulations etc.)</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_oreg_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_oreg_yes" name="other_regulations" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_oreg_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_oreg_no" name="other_regulations" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_oreg_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_oreg_na" name="other_regulations" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="other_regulations_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Has the installation been carried out to the relevant standard / manufacturers instructions?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_smif_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_smif_yes" name="instruction_followed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_smif_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_smif_no" name="instruction_followed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Operating Pressure</x-base.form-label>
                        <x-base.input-group class="w-full h-[35px] rounded-[3px]" inputGroup >
                            <x-base.form-input type="number" step="any" name="opt_pressure" />
                            <div class="inline-flex justify-end items-center h-full">
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="opt_pressure_unit" id="opt_pressure_unit_mbar" value="mbar" />
                                    <label class="inline-flex border-r peer-checked:bg-success peer-checked:text-white border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="opt_pressure_unit_mbar">
                                        mbar
                                    </label>
                                </div>
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="opt_pressure_unit" id="opt_pressure_unit_kwh" value="KW/h" />
                                    <label class="inline-flex peer-checked:bg-success peer-checked:text-white border-r border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="opt_pressure_unit_kwh">
                                        KW/h
                                    </label>
                                </div>
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="opt_pressure_unit" id="opt_pressure_unit_btuh" value="BTU/h" />
                                    <label class="inline-flex peer-checked:bg-success peer-checked:text-white border-r border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full rounded-tr-[3px] rounded-br-[3px]" for="opt_pressure_unit_btuh">
                                        BTU/h
                                    </label>
                                </div>
                            </div>
                        </x-base.input-group>
                    </div>


                    <div class="col-span-12">
                        <h2 class="mb-4 mt-5 font-medium text-base leading-none tracking-normal">Appliance - Satisfactory</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Burner / Injectors</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isbi_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isbi_yes" name="burner_injectors" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isbi_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isbi_no" name="burner_injectors" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isbi_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isbi_na" name="burner_injectors" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Burner / Injectors: Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="burner_injectors_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Ignition and flame picture</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_igtn_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_igtn_yes" name="ignition" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_igtn_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_igtn_no" name="ignition" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_igtn_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_igtn_na" name="ignition" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="ignition_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Heat Exchanger</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hex_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hex_yes" name="heat_exchanger" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hex_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hex_no" name="heat_exchanger" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hex_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hex_na" name="heat_exchanger" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Heat Exchanger: Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="heat_exchanger_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Electrical connection</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_elec_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_elec_yes" name="electrics" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_elec_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_elec_no" name="electrics" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_elec_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_elec_na" name="electrics" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="electrics_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance/ System Controls</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ctrls_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ctrls_yes" name="Appliance/ System Controlscontrols" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ctrls_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ctrls_no" name="controls" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ctrls_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ctrls_na" name="controls" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="controls_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Fans</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fan_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fan_yes" name="fans" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fan_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fan_no" name="fans" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fan_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fan_na" name="fans" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="fans_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Seals (appliance case etc.)</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_seals_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_seals_yes" name="seals" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_seals_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_seals_no" name="seals" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_seals_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_seals_na" name="seals" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="seals_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Fireplace catchment space</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fire_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fire_yes" name="fireplace" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fire_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fire_no" name="fireplace" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fire_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fire_na" name="fireplace" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="fireplace_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Closure plate</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_prs10_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_prs10_yes" name="closure_plate" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_prs10_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_prs10_no" name="closure_plate" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_prs10_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_prs10_na" name="closure_plate" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="closure_plate_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Return air/plenum</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rapl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rapl_yes" name="return_air_ple" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rapl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rapl_no" name="return_air_ple" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rapl_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rapl_na" name="return_air_ple" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="return_air_ple_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Allowable Location</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_allwl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_allwl_yes" name="allowable_location" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_allwl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_allwl_no" name="allowable_location" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_allwl_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_allwl_na" name="allowable_location" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="allowable_location_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Stability</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stbl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stbl_yes" name="stability" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stbl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stbl_no" name="stability" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stbl_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stbl_na" name="stability" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="stability_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Working Pressure</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_wprs_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_wprs_yes" name="working_pressure" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_wprs_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_wprs_no" name="working_pressure" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_wprs_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_wprs_na" name="working_pressure" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="working_pressure_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Expansion Vassel checked / recharged?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_exvcr_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_exvcr_yes" name="expansion_vassel_checked" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_exvcr_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_exvcr_no" name="expansion_vassel_checked" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_exvcr_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_exvcr_na" name="expansion_vassel_checked" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled name="expansion_vassel_checked_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is the Installation and appliance safe to use?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iastu_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iastu_yes" name="is_safe_to_use" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iastu_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iastu_no" name="is_safe_to_use" class="hasDetails absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea disabled disabled name="is_safe_to_use_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
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