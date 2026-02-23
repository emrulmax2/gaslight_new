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
                        <x-base.form-label class="mb-1">Appliance Serial Number</x-base.form-label>
                        <x-base.form-input value="" name="serial_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Time and temperature control to heating</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="appliance_time_temperature_heating_id" name="appliance_time_temperature_heating_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($timerTemp->count() > 0)
                                @foreach($timerTemp as $option)
                                    <option {{ (isset($gbscca1->appliance_time_temperature_heating_id) && $gbscca1->appliance_time_temperature_heating_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Time and temperature control to hot water</x-base.form-label>
                        <x-base.form-select class="w-full h-[35px] rounded-[3px]" id="tmp_control_hot_water" name="tmp_control_hot_water" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            <option value="Cylinder thermostat and programmer/timer">Cylinder thermostat and programmer/timer</option>
                            <option value="Combination boiler">Combination boiler</option>
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Heating zone valves</x-base.form-label>
                        <x-base.form-select class="w-full h-[35px] rounded-[3px]" id="heating_zone_vlv" name="heating_zone_vlv" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            <option value="Fitted">Fitted</option>
                            <option value="Not Required">Not Required</option>
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Hot water zone valves</x-base.form-label>
                        <x-base.form-select class="w-full h-[35px] rounded-[3px]" id="hot_water_zone_vlv" name="hot_water_zone_vlv" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            <option value="Fitted">Fitted</option>
                            <option value="Not Required">Not Required</option>
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Thermostic radiator valves</x-base.form-label>
                        <x-base.form-select class="w-full h-[35px] rounded-[3px]" id="therm_radiator_vlv" name="therm_radiator_vlv" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            <option value="Fitted">Fitted</option>
                            <option value="Not Required">Not Required</option>
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Automatic bypass to system</x-base.form-label>
                        <x-base.form-select class="w-full h-[35px] rounded-[3px]" id="bypass_to_system" name="bypass_to_system" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            <option value="Fitted">Fitted</option>
                            <option value="Not Required">Not Required</option>
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Boiler interlock</x-base.form-label>
                        <x-base.form-select class="w-full h-[35px] rounded-[3px]" id="boiler_interlock" name="boiler_interlock" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            <option value="Provided">Provided</option>
                            <option value="Not Required">Not Required</option>
                        </x-base.form-select>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">All System</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">The system has been flushed and cleaned in accordance with BS7593 and boiler manufacturer's instructions</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_flscln_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_flscln_yes" name="flushed_and_cleaned" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_flscln_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_flscln_no" name="flushed_and_cleaned" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">What cleaner was used?</x-base.form-label>
                        <x-base.form-input name="clearner_name" value="" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Clearner Name" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">What inhibitor was used?</x-base.form-label>
                        <div class="block">
                            <x-base.form-input name="inhibitor_quantity" value="" class="w-full mb-0 h-[35px] rounded-[3px] rounded-bl-none rounded-br-none" type="text" placeholder="Quantity" />
                            <x-base.form-input name="inhibitor_amount" value="" class="w-full h-[35px] mt-0 rounded-[3px] rounded-tl-none rounded-tr-none" type="text" placeholder="Liters" />
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Has a primary water system filter been installed</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hpwsfbi_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hpwsfbi_yes" name="primary_ws_filter_installed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hpwsfbi_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hpwsfbi_no" name="primary_ws_filter_installed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Central Heating Mode</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Gas Rate</x-base.form-label>
                        <x-base.input-group class="w-full h-[35px] rounded-[3px]" inputGroup >
                            <x-base.form-input type="number" step="any" name="gas_rate" placeholder="Gas Rate" />
                            <div class="inline-flex justify-end items-center h-full">
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="gas_rate_unit" id="gas_rate_unit_1" value="m3/hr" />
                                    <label class="inline-flex border-r peer-checked:bg-success peer-checked:text-white border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="gas_rate_unit_1">
                                        m<sup>3</sup>/hr
                                    </label>
                                </div>
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="gas_rate_unit" id="gas_rate_unit_2" value="ft3/hr" />
                                    <label class="inline-flex peer-checked:bg-success peer-checked:text-white border-r border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="gas_rate_unit_2">
                                        ft<sup>3</sup>/hr
                                    </label>
                                </div>
                            </div>
                        </x-base.input-group>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Central heating output left at factory setting</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_cholafs_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_cholafs_yes" name="cho_factory_setting" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_cholafs_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_cholafs_no" name="cho_factory_setting" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_cholafs_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_cholafs_na" name="cho_factory_setting" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Operating pressure (or inlet pressure)</x-base.form-label>
                        <x-base.input-group class="w-full h-[35px] rounded-[3px]" inputGroup >
                            <x-base.form-input type="number" step="any" name="burner_opt_pressure" placeholder="Buner pressure" />
                            <div class="inline-flex justify-end items-center h-full">
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="burner_opt_pressure_unit" id="burner_opt_pressure_unit_1" value="mbar" />
                                    <label class="inline-flex border-r peer-checked:bg-success peer-checked:text-white border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="burner_opt_pressure_unit_1">
                                        mbar
                                    </label>
                                </div>
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="burner_opt_pressure_unit" id="burner_opt_pressure_unit_2" value="kW/h" />
                                    <label class="inline-flex peer-checked:bg-success peer-checked:text-white border-r border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="burner_opt_pressure_unit_2">
                                        kW/h
                                    </label>
                                </div>
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="burner_opt_pressure_unit" id="burner_opt_pressure_unit_3" value="Btu/h" />
                                    <label class="inline-flex peer-checked:bg-success peer-checked:text-white border-r border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="burner_opt_pressure_unit_3">
                                        Btu/h
                                    </label>
                                </div>
                            </div>
                        </x-base.input-group>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Central heating flow temperature</x-base.form-label>
                        <x-base.form-input name="centeral_heat_flow_temp" value="" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Central heating return temperature</x-base.form-label>
                        <x-base.form-input name="centeral_heat_return_temp" value="" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Combination boilers only</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is the installation in a hard water area (above 200ppm)</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_itiiahwara2_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_itiiahwara2_yes" name="is_in_hard_water_area" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_itiiahwara2_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_itiiahwara2_no" name="is_in_hard_water_area" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 maufacturerWrap" style="display: none;">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">If required by the manufacturer, has the water scale reducer been fitted?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iyairmsf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iyairmsf_yes" name="is_scale_reducer_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_iyairmsf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_iyairmsf_no" name="is_scale_reducer_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 reducerFittedWrap" style="display: none;">
                        <x-base.form-label class="mb-1">What type of scale reducer been fitted?</x-base.form-label>
                        <x-base.form-input name="what_reducer_fitted" value="" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Domestic hot water mode</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Gas Rate</x-base.form-label>
                        <x-base.input-group class="w-full h-[35px] rounded-[3px]" inputGroup >
                            <x-base.form-input type="number" step="any" name="dom_gas_rate" placeholder="Gas Rate" />
                            <div class="inline-flex justify-end items-center h-full">
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="dom_gas_rate_unit" id="dom_gas_rate_unit_1" value="m3/hr" />
                                    <label class="inline-flex border-r peer-checked:bg-success peer-checked:text-white border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="dom_gas_rate_unit_1">
                                        m<sup>3</sup>/hr
                                    </label>
                                </div>
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="dom_gas_rate_unit" id="dom_gas_rate_unit_2" value="ft3/hr" />
                                    <label class="inline-flex peer-checked:bg-success peer-checked:text-white border-r border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="dom_gas_rate_unit_2">
                                        ft<sup>3</sup>/hr
                                    </label>
                                </div>
                            </div>
                        </x-base.input-group>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Operating pressure (or inlet pressure)</x-base.form-label>
                        <x-base.input-group class="w-full h-[35px] rounded-[3px]" inputGroup >
                            <x-base.form-input type="number" step="any" name="dom_burner_opt_pressure" placeholder="Buner pressure" />
                            <div class="inline-flex justify-end items-center h-full">
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="dom_burner_opt_pressure_unit" id="dom_burner_opt_pressure_unit_1" value="mbar" />
                                    <label class="inline-flex border-r peer-checked:bg-success peer-checked:text-white border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="dom_burner_opt_pressure_unit_1">
                                        mbar
                                    </label>
                                </div>
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="dom_burner_opt_pressure_unit" id="dom_burner_opt_pressure_unit_2" value="kW/h" />
                                    <label class="inline-flex peer-checked:bg-success peer-checked:text-white border-r border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="dom_burner_opt_pressure_unit_2">
                                        kW/h
                                    </label>
                                </div>
                                <div class="relative h-full">
                                    <input type="radio" class="peer absolute w-0 h-0 opacity-0" name="dom_burner_opt_pressure_unit" id="dom_burner_opt_pressure_unit_3" value="Btu/h" />
                                    <label class="inline-flex peer-checked:bg-success peer-checked:text-white border-r border-r-slate-100 items-center justify-center relative bg-slate-200 text-slate-600 cursor-pointer font-meidum px-2 py-1 h-full" for="dom_burner_opt_pressure_unit_3">
                                        Btu/h
                                    </label>
                                </div>
                            </div>
                        </x-base.input-group>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Cold water inlet temperature</x-base.form-label>
                        <x-base.form-input name="dom_cold_water_temp" value="" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Hot water has been checked at all outlet</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ditiiahwara2_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ditiiahwara2_yes" name="dom_checked_outlet" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_ditiiahwara2_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_ditiiahwara2_no" name="dom_checked_outlet" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Water flow rate</x-base.form-label>
                        <x-base.form-input name="dom_water_flow_rate" value="" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Condensing Boilers Only</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">The condensate drain has been installed in accordance with the manufacturer's instructions and/or BS5546/BS6798</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_tcdhbiam_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_tcdhbiam_yes" name="con_drain_installed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_tcdhbiam_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_tcdhbiam_no" name="con_drain_installed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Point of termination</x-base.form-label>
                        <x-base.form-select class="w-full h-[35px] rounded-[3px]" id="point_of_termination" name="point_of_termination" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            <option value="Internal">Internal</option>
                            <option value="External">External</option>
                            <option value="N/A">N/A</option>
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Method of disposal</x-base.form-label>
                        <x-base.form-select class="w-full h-[35px] rounded-[3px]" id="dispsal_method" name="dispsal_method" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            <option value="Gravity">Gravity</option>
                            <option value="Pumped">Pumped</option>
                            <option value="N/A">N/A</option>
                        </x-base.form-select>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">All Installations</h2>
                    </div>
                    <div class="col-span-12 pt-2">
                        <x-base.form-label class="mb-1">Min Readings</x-base.form-label>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="min_ratio" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="Ratio" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO (PPM)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="min_co" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO (PPM)" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO<sub>2</sub> (%)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="min_co2" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO2 (%)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 pt-2">
                        <x-base.form-label class="mb-1">Max Readings</x-base.form-label>
                        <div class="grid grid-cols-12 gap-x-5 gap-y-3">
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">RATIO</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="max_ratio" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="Ratio" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO (PPM)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="max_co" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO (PPM)" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <div class="readings">
                                    <x-base.form-label class="mb-1 text-center block">CO<sub>2</sub> (%)</x-base.form-label>
                                    <div class="block">
                                        <x-base.form-input value="" name="max_co2" class="w-full h-[33px] rounded-[3px]" type="text" placeholder="CO2 (%)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">The heating and hot water system complies with the appropriate Building Regulations</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_apprbuilreg_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_apprbuilreg_yes" name="app_building_regulation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_apprbuilreg_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_apprbuilreg_no" name="app_building_regulation" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">The boiler and associated products have been installed and commissioned in accordance with the manufacturer's instructions</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_baicami_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_baicami_yes" name="commissioned_man_ins" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_baicami_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_baicami_no" name="commissioned_man_ins" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">The operation of the boiler system controls have been demonstrated to and understood by the customer</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_bscduc_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_bscduc_yes" name="demonstrated_understood" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_bscduc_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_bscduc_no" name="demonstrated_understood" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">The manufacturer's literature, including Benchmark Checklist and Service Record, has been explained and left with the customer</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mlibcsrex_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mlibcsrex_yes" name="literature_including" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mlibcsrex_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mlibcsrex_no" name="literature_including" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Next Inspection</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Does the next inspection apply to this certificate?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_dtniatc_yes">Applicable</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_dtniatc_yes" name="is_next_inspection" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Applicable"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_dtniatc_no">Not Applicable</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_dtniatc_no" name="is_next_inspection" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Not Applicable"/>
                                </x-base.form-check>
                            </div>
                            <div class="text-xs text-slate-500 mt-2">
                                If "Not Applicable" selected for this option, this inspection date will not be displayed on this certificate and
                                the reminder for this certificate will not be scheduled.
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