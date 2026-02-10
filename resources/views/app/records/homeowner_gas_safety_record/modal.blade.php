<!-- BEGIN: Appliance Modal Content -->
<x-base.dialog id="applianceModal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="applianceForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add Appliance</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <input type="hidden" name="appliance_serial" value="0"/>
                <input type="hidden" name="edit" value="0"/>
                <h3 class="font-medium mb-5 text-base leading-none">Appliance Details</h3>
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
                        <x-base.form-label class="mb-1">Flue Type</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0 pl-0" id="appliance_flue_type_id" name="appliance_flue_type_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($flue_types->count() > 0)
                                @foreach($flue_types as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Operating Pressure</x-base.form-label>
                        <!-- <x-base.form-input value="" name="opt_pressure" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Operating Pressure" /> -->
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
                    <div class="col-span-12 sm:col-span-4"></div>
                    <div class="col-span-12 pt-5">
                        <h3 class="font-medium mb-5 text-base leading-none">Flue Tests</h3>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Operation of Safety Device</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_sd_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_sd_yes" name="safety_devices" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_sd_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_sd_no" name="safety_devices" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_sd_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_sd_na" name="safety_devices" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Spillage Test</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_spgt_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_spgt_yes" name="spillage_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_spgt_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_spgt_no" name="spillage_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_spgt_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_spgt_na" name="spillage_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Smoke Pellet Flue Flow Test</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_spfft_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_spfft_yes" name="smoke_pellet_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_spfft_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_spfft_no" name="smoke_pellet_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_spfft_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_spfft_na" name="smoke_pellet_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 pt-2">
                        <x-base.form-label class="mb-1">Inital (low) Combustion Analyser Reading</x-base.form-label>
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
                        <x-base.form-label class="mb-1">Final (high) Combustion Analyser Reading</x-base.form-label>
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
                    <div class="col-span-12 pt-5">
                        <h3 class="font-medium mb-5 text-base leading-none">Inspection Details</h3>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Satisfactory Termination</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_sft_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_sft_yes" name="satisfactory_termination" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_sft_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_sft_no" name="satisfactory_termination" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_sft_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_sft_na" name="satisfactory_termination" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Flue Visual Condition</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_fvc_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_fvc_yes" name="flue_visual_condition" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_fvc_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_fvc_no" name="flue_visual_condition" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_fvc_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_fvc_na" name="flue_visual_condition" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Ventilation Satisfactory</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_av_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_av_yes" name="adequate_ventilation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_av_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_av_no" name="adequate_ventilation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Landlord's Appliance</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_la_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_la_yes" name="landlord_appliance" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_la_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_la_no" name="landlord_appliance" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Inspected</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_ipt_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_ipt_yes" name="inspected" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_ipt_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_ipt_no" name="inspected" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance Visual Check</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_avc_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_avc_yes" name="appliance_visual_check" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_avc_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_avc_no" name="appliance_visual_check" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_avc_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_avc_na" name="appliance_visual_check" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance Serviced</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_as_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_as_yes" name="appliance_serviced" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_as_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_as_no" name="appliance_serviced" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Appliance Safe to Use</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_asu_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_asu_yes" name="appliance_safe_to_use" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_1_asu_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_1_asu_no" name="appliance_safe_to_use" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
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


<!-- BEGIN: Safety Check Modal Content -->
<x-base.dialog id="safetyCheckModal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="safetyCheckForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Safety Checks</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12">
                        <h3 class="font-bold text-base tracking-normal">Gas Installation Pipework</h3>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Satisfactory Visual Inspection</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_svi_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_svi_yes" name="satisfactory_visual_inspaction" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_svi_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_svi_no" name="satisfactory_visual_inspaction" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_svi_nas">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_svi_nas" name="satisfactory_visual_inspaction" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Emergency Control Accessible</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_eca_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_eca_yes" name="emergency_control_accessible" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_eca_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_eca_no" name="emergency_control_accessible" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Satisfactory Gas Tightness Test</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_sgtt_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_sgtt_yes" name="satisfactory_gas_tightness_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_sgtt_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_sgtt_no" name="satisfactory_gas_tightness_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_sgtt_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_sgtt_na" name="satisfactory_gas_tightness_test" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Protective Equipotential Bonding Satisfactory</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_ebs_yes">Pass</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_ebs_yes" name="equipotential_bonding_satisfactory" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Pass"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_ebs_no">Fail</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_ebs_no" name="equipotential_bonding_satisfactory" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Fail"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_ebs_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_ebs_na" name="equipotential_bonding_satisfactory" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 pt-3">
                        <h3 class="font-bold text-base tracking-normal">Audible CO Alarms</h3>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Approved CO Alarm Fitted</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_acoaf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_acoaf_yes" name="co_alarm_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_acoaf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_acoaf_no" name="co_alarm_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_acoaf_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_acoaf_na" name="co_alarm_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Are CO Alarm in Date</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_acoid_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_acoid_yes" name="co_alarm_in_date" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_acoid_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_acoid_no" name="co_alarm_in_date" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_acoid_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_acoid_na" name="co_alarm_in_date" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Testing of CO Alarm Satisfactory</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_tcoas_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_tcoas_yes" name="co_alarm_test_satisfactory" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_tcoas_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_tcoas_no" name="co_alarm_test_satisfactory" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_tcoas_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_tcoas_na" name="co_alarm_test_satisfactory" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Smoke Alarms Fitted</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_saf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_saf_yes" name="smoke_alarm_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_saf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_saf_no" name="smoke_alarm_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_gip_saf_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_gip_saf_na" name="smoke_alarm_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveSafetyBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Safety Check Modal Content -->

<!-- BEGIN: Comments Modal Content -->
<x-base.dialog id="commentsModal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="commentsForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Comments</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
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
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Has Flue Cap Been Put Back?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_hfcbpb_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_hfcbpb_yes" name="flue_cap_put_back" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_hfcbpb_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_hfcbpb_no" name="flue_cap_put_back" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="app_4_hfcbpb_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="app_4_hfcbpb_na" name="flue_cap_put_back" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveCommentsBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Comments Modal Content -->