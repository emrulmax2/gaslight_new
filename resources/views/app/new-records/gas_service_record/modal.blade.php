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
                        <x-base.form-label>Operating Pressure (mbar) or Heat Input (KW/h) or (BTU/h)</x-base.form-label>
                        <x-base.form-input value="" name="opt_pressure" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Operating Pressure" />
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
                            <x-base.form-label class="mb-2 mt-1 block font-medium">If a gas test has been carried out, was this a pass or fail</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_tcro_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_tcro_yes" name="test_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_tcro_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_tcro_no" name="test_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_tcro_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_tcro_na" name="test_carried_out" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is electrical bonding (where required satisfactory)</x-base.form-label>
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
                    <div class="col-span-12 pt-2">
                        <x-base.form-label>Inital (low) Combustion Analyser Reading</x-base.form-label>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="low_analyser_ratio" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                        <x-base.form-input value="" name="low_co" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                        <x-base.form-input value="" name="low_co2" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
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
                                        <x-base.form-input value="" name="high_analyser_ratio" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="Ratio" />
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
                                        <x-base.form-input value="" name="high_co" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO (PPM)" />
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
                                        <x-base.form-input value="" name="high_co2" class="w-full h-[33px] rounded-[3px] rounded-b-none" type="text" placeholder="CO2 (%)" />
                                        <x-base.button data-value="N/A" type="button" class="naToggleBtn w-full rounded-t-none h-[33px] bg-slate-500 border-slate-500 text-white hover:bg-slate-500 hover:border-slate-500 hover:opacity-80">
                                            N/A
                                        </x-base.button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Heat Exchanger</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hex_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hex_yes" name="heat_exchanger" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hex_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hex_no" name="heat_exchanger" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hex_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hex_na" name="heat_exchanger" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Heat Exchanger: Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="heat_exchanger_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Burner / Injectors</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isbi_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isbi_yes" name="burner_injectors" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isbi_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isbi_no" name="burner_injectors" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isbi_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isbi_na" name="burner_injectors" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Burner / Injectors: Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="burner_injectors_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Flame Picture</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isfp_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isfp_yes" name="flame_picture" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isfp_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isfp_no" name="flame_picture" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isfp_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isfp_na" name="flame_picture" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="flame_picture_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Ignition</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_igtn_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_igtn_yes" name="ignition" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_igtn_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_igtn_no" name="ignition" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_igtn_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_igtn_na" name="ignition" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="ignition_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Electrics</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_elec_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_elec_yes" name="electrics" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_elec_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_elec_no" name="electrics" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_elec_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_elec_na" name="electrics" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="electrics_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Controls</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ctrls_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ctrls_yes" name="controls" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ctrls_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ctrls_no" name="controls" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ctrls_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ctrls_na" name="controls" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="controls_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Leaks Gas / Water</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_lgw_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_lgw_yes" name="leak_gas_water" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_lgw_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_lgw_no" name="leak_gas_water" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_lgw_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_lgw_na" name="leak_gas_water" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="leak_gas_water_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Seals</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_seals_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_seals_yes" name="seals" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_seals_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_seals_no" name="seals" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_seals_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_seals_na" name="seals" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="seals_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Pipework</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_pipw_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_pipw_yes" name="pipework" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_pipw_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_pipw_no" name="pipework" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_pipw_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_pipw_na" name="pipework" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="pipework_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Fans</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fan_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fan_yes" name="fans" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fan_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fan_no" name="fans" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fan_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fan_na" name="fans" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="fans_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Fireplace</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fire_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fire_yes" name="fireplace" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fire_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fire_no" name="fireplace" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fire_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fire_na" name="fireplace" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="fireplace_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Closure Plate & PRS10 Tape</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_prs10_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_prs10_yes" name="closure_plate" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_prs10_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_prs10_no" name="closure_plate" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_prs10_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_prs10_na" name="closure_plate" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="closure_plate_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Allowable Location</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_allwl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_allwl_yes" name="allowable_location" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_allwl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_allwl_no" name="allowable_location" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_allwl_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_allwl_na" name="allowable_location" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="allowable_location_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Boiler Ratio</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_brat_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_brat_yes" name="boiler_ratio" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_brat_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_brat_no" name="boiler_ratio" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_brat_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_brat_na" name="boiler_ratio" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="boiler_ratio_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Stability</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stbl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stbl_yes" name="stability" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stbl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stbl_no" name="stability" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_stbl_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_stbl_na" name="stability" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="stability_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Return Air / Plenum</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rapl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rapl_yes" name="return_air_ple" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rapl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rapl_no" name="return_air_ple" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_rapl_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_rapl_na" name="return_air_ple" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="return_air_ple_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Ventillation</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ventl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ventl_yes" name="ventillation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ventl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ventl_no" name="ventillation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ventl_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ventl_na" name="ventillation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="ventillation_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Flue Termination</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_flut_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_flut_yes" name="flue_termination" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_flut_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_flut_no" name="flue_termination" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_flut_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_flut_na" name="flue_termination" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="flue_termination_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Smoke Pellet Flue Flow Test</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_spfft_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_spfft_yes" name="smoke_pellet_flue_flow" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_spfft_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_spfft_no" name="smoke_pellet_flue_flow" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_spfft_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_spfft_na" name="smoke_pellet_flue_flow" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="smoke_pellet_flue_flow_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Smoke Match Spillage Test</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_smst_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_smst_yes" name="smoke_pellet_spillage" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_smst_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_smst_no" name="smoke_pellet_spillage" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_smst_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_smst_na" name="smoke_pellet_spillage" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="smoke_pellet_spillage_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Working Pressure</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_wprs_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_wprs_yes" name="working_pressure" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_wprs_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_wprs_no" name="working_pressure" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_wprs_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_wprs_na" name="working_pressure" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="working_pressure_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Safety Devices</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_sftd_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_sftd_yes" name="savety_devices" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_sftd_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_sftd_no" name="savety_devices" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_sftd_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_sftd_na" name="savety_devices" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="savety_devices_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Gas Tightness test performed</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_gstn_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_gstn_yes" name="gas_tightness" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_gstn_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_gstn_no" name="gas_tightness" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_gstn_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_gstn_na" name="gas_tightness" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="gas_tightness_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Expansion Vassel checked / recharged?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_exvcr_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_exvcr_yes" name="expansion_vassel_checked" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_exvcr_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_exvcr_no" name="expansion_vassel_checked" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_exvcr_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_exvcr_na" name="expansion_vassel_checked" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="expansion_vassel_checked_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Other (regulations etc.)</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_oreg_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_oreg_yes" name="other_regulations" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_oreg_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_oreg_no" name="other_regulations" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_oreg_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_oreg_na" name="other_regulations" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-8">
                        <x-base.form-label class="mb-1">Defects found / remedial action taken</x-base.form-label>
                        <x-base.form-textarea name="other_regulations_detail" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is the Installation and appliance safe to use?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iastu_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iastu_yes" name="is_safe_to_use" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iastu_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iastu_no" name="is_safe_to_use" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iastu_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iastu_na" name="is_safe_to_use" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
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
                    <div class="col-span-12">
                        <x-base.form-label class="mb-1">Necessary remedial work required</x-base.form-label>
                        <x-base.form-textarea name="work_required_note" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
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