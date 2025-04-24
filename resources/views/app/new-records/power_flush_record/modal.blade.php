<!-- BEGIN: Powerflush Checklist Modal Content -->
<x-base.dialog id="powerflushChecklistModal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="powerflushChecklistForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Powerflush Checklist</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Type of System</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="powerflush_system_type_id" name="powerflush_system_type_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($flush_types->count() > 0)
                                @foreach($flush_types as $option)
                                    <option {{ (isset($gpfrc->powerflush_system_type_id) && $gpfrc->powerflush_system_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Age of System</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Boiler</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="boiler_brand_id" name="boiler_brand_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($boilers->count() > 0)
                                @foreach($boilers as $option)
                                    <option {{ (isset($gpfrc->boiler_brand_id) && $gpfrc->boiler_brand_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Radiators</x-base.form-label>
                        <x-base.form-input value="" name="radiators" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Pipework</x-base.form-label>
                        <x-base.form-input value="" name="pipework" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Type of boiler</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="appliance_type_id" name="appliance_type_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($types->count() > 0)
                                @foreach($types as $option)
                                    <option {{ (isset($gpfrc->appliance_type_id) && $gpfrc->appliance_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Location of Boiler</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="appliance_location_id" name="appliance_location_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($locations->count() > 0)
                                @foreach($locations as $option)
                                    <option {{ (isset($gpfrc->appliance_location_id) && $gpfrc->appliance_location_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Serial Number</x-base.form-label>
                        <x-base.form-input value="" name="serial_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="Serial Number" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Type of Water Cylinder</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="powerflush_cylinder_type_id" name="powerflush_cylinder_type_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($flush_cylinder->count() > 0)
                                @foreach($flush_cylinder as $option)
                                    <option {{ (isset($gpfrc->powerflush_cylinder_type_id) && $gpfrc->powerflush_cylinder_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Type of Pipework</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="powerflush_pipework_type_id" name="powerflush_pipework_type_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($flush_pipework->count() > 0)
                                @foreach($flush_pipework as $option)
                                    <option {{ (isset($gpfrc->powerflush_pipework_type_id) && $gpfrc->powerflush_pipework_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">If microbore system</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Are twin entry radiator valves fitted</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_atervf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_atervf_yes" name="twin_radiator_vlv_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_atervf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_atervf_no" name="twin_radiator_vlv_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">If so, are all radiators completely warm when boiler fired</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isaarcwwbf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isaarcwwbf_yes" name="completely_warm_on_fired" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_isaarcwwbf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_isaarcwwbf_no" name="completely_warm_on_fired" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">If single pipe system</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is there circulation (heat) to all radiators</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_itchtar_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_itchtar_yes" name="circulation_for_all_readiators" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_itchtar_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_itchtar_no" name="circulation_for_all_readiators" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">If elderly steel pipework</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is system sufficiently sound to power flush</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_issstpf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_issstpf_yes" name="suffifiently_sound" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_issstpf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_issstpf_no" name="suffifiently_sound" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Location of system circulator pump</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="powerflush_circulator_pump_location_id" name="powerflush_circulator_pump_location_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($flush_pump_location->count() > 0)
                                @foreach($flush_pump_location as $option)
                                    <option {{ (isset($gpfrc->powerflush_circulator_pump_location_id) && $gpfrc->powerflush_circulator_pump_location_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Number of radiators</x-base.form-label>
                        <x-base.form-input value="{{ (isset($gpfrc->number_of_radiators) ? $gpfrc->number_of_radiators : '') }}" name="number_of_radiators" class="w-full h-[35px] rounded-[3px]" type="number" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Radiator Type</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="radiator_type_id" name="radiator_type_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($radiator_type->count() > 0)
                                @foreach($radiator_type as $option)
                                    <option {{ (isset($gpfrc->radiator_type_id) && $gpfrc->radiator_type_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Are they getting warm</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_atgw_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_atgw_yes" name="getting_warm" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_atgw_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_atgw_no" name="getting_warm" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Are TRV's fitted</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_atrvf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_atrvf_yes" name="are_trvs_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_atrvf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_atrvf_no" name="are_trvs_fitted" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Any obvious signs of neglect/leak</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aosonl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aosonl_yes" name="sign_of_neglect" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_aosonl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_aosonl_no" name="sign_of_neglect" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Do all thermostic radiator valves (TRV's) open fully</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_datrvof_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_datrvof_yes" name="radiator_open_fully" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_datrvof_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_datrvof_no" name="radiator_open_fully" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">All there zone valves / Where are they located</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Number of valves</x-base.form-label>
                        <x-base.form-input value="{{ (isset($gpfrc->number_of_valves) ? $gpfrc->number_of_valves : '') }}" name="number_of_valves" class="w-full h-[35px] rounded-[3px]" type="number" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Location</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_atzvl_yes">Airing Cupboard</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_atzvl_yes" name="valves_located" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Airing Cupboard"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_datrvof_no">Elsewhere</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_atzvl_no" name="valves_located" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Elsewhere"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">F & E Tank</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Location</x-base.form-label>
                        <x-base.form-input value="{{ (isset($gpfrc->fe_tank_location) ? $gpfrc->fe_tank_location : '') }}" name="fe_tank_location" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Checked</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fetnkck_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fetnkck_yes" name="fe_tank_checked" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_fetnkck_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_fetnkck_no" name="fe_tank_checked" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Condition</x-base.form-label>
                        <x-base.form-input value="{{ (isset($gpfrc->fe_tank_condition) ? $gpfrc->fe_tank_condition : '') }}" name="fe_tank_condition" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Color of heating system water, as run from bottom of radiator</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="color_id" name="color_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($color->count() > 0)
                                @foreach($color as $option)
                                    <option {{ (isset($gpfrc->color_id) && $gpfrc->color_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Visual inspection of system water before PowerFlush</x-base.form-label>
                        <x-base.form-select class="w-full tom-select py-0" id="before_color_id" name="before_color_id" data-placeholder="Please Select">
                            <option value="">Please Select</option>
                            @if($color->count() > 0)
                                @foreach($color as $option)
                                    <option {{ (isset($gpfrc->before_color_id) && $gpfrc->before_color_id == $option->id ? 'Selected' : '') }} value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            @endif
                        </x-base.form-select>
                    </div>
                    <div class="col-span-12">
                        <div class="overflow-x-auto">
                            <x-base.table bordered sm>
                                <x-base.table.thead>
                                    <x-base.table.tr>
                                        <x-base.table.th class="whitespace-normal text-left max-sm:text-xs max-sm:p-1">Test Parameter</x-base.table.th>
                                        <x-base.table.th class="whitespace-normal text-left max-sm:text-xs max-sm:p-1">pH</x-base.table.th>
                                        <x-base.table.th class="whitespace-normal text-left max-sm:text-xs max-sm:p-1">chloride (ppm)</x-base.table.th>
                                        <x-base.table.th class="whitespace-normal text-left max-sm:text-xs max-sm:p-1">Hardness</x-base.table.th>
                                        <x-base.table.th class="whitespace-normal text-left max-sm:text-xs max-sm:p-1">Inhibitor (ppm molybdate)</x-base.table.th>
                                    </x-base.table.tr>
                                </x-base.table.thead>
                                <x-base.table.tbody>
                                    <x-base.table.tr>
                                        <x-base.table.th class="whitespace-normal text-left max-sm:text-xs max-sm:p-1">Mains water</x-base.table.th>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="mw_ph" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="mw_chloride" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="mw_hardness" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="mw_inhibitor" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    </x-base.table.tr>
                                    <x-base.table.tr>
                                        <x-base.table.th class="whitespace-normal text-left max-sm:text-xs max-sm:p-1">System water before PowerFlush</x-base.table.th>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="bpf_ph" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="bpf_chloride" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="bpf_hardness" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="bpf_inhibitor" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    </x-base.table.tr>
                                    <x-base.table.tr>
                                        <x-base.table.th class="whitespace-normal text-left max-sm:text-xs max-sm:p-1">System water after PowerFlush</x-base.table.th>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="apf_ph" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="apf_chloride" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="apf_hardness" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                        <x-base.table.td class="whitespace-normal text-left max-sm:p-1"><x-base.form-input value="" name="apf_inhibitor" class="w-[80px] sm:w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    </x-base.table.tr>
                                </x-base.table.tbody>
                            </x-base.table>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">TDS Readings</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Mains Water</x-base.form-label>
                        <x-base.input-group class="mt-0" inputGroup >
                            <x-base.form-input value="{{ (isset($gpfrc->mw_tds_reading) ? $gpfrc->mw_tds_reading : '') }}" name="mw_tds_reading" class="w-full rounded-[3px]" type="text" placeholder="" />
                            <x-base.input-group.text>ppm</x-base.input-group.text>
                        </x-base.input-group>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>System water before flush</x-base.form-label>
                        <x-base.input-group class="mt-0" inputGroup >
                            <x-base.form-input value="{{ (isset($gpfrc->bf_tds_reading) ? $gpfrc->bf_tds_reading : '') }}" name="bf_tds_reading" class="w-full rounded-[3px]" type="text" placeholder="" />
                            <x-base.input-group.text>ppm</x-base.input-group.text>
                        </x-base.input-group>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>System water after flush</x-base.form-label>
                        <x-base.input-group class="mt-0" inputGroup >
                            <x-base.form-input value="{{ (isset($gpfrc->af_tds_reading) ? $gpfrc->af_tds_reading : '') }}" name="af_tds_reading" class="w-full rounded-[3px]" type="text" placeholder="" />
                            <x-base.input-group.text>ppm</x-base.input-group.text>
                        </x-base.input-group>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveChecklistBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Powerflush Checklist Modal Content -->


<!-- BEGIN: Radiator Modal Content -->
<x-base.dialog id="radiatorModal" staticBackdrop size="lg">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="radiatorForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add Radiator</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <input type="hidden" name="radiator_serial" value="0"/>
                <input type="hidden" name="edit" value="0"/>
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Rediator Location</label>
                        <input value="" name="rediator_location" type="text" placeholder="" class="reaiator_location_name disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>
                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Temperature before powerflus in °C</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Top</label>
                        <input value="" name="tmp_b_top" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Bottom</label>
                        <input value="" name="tmp_b_bottom" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Left</label>
                        <input value="" name="tmp_b_left" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Right</label>
                        <input value="" name="tmp_b_right" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Temperature After powerflus in °C</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Top</label>
                        <input value="" name="tmp_a_top" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Bottom</label>
                        <input value="" name="tmp_a_bottom" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Left</label>
                        <input value="" name="tmp_a_left" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <label class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">Right</label>
                        <input value="" name="tmp_a_right" type="text" placeholder="" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 w-full h-[35px] rounded-[3px]">
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveRadiatorBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Appliance Modal Content -->