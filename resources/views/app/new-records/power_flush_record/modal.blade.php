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
                        <x-base.form-label>Are twin entry radiator valves fitted</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_atervf_yes" name="twin_radiator_vlv_fitted" {{ (isset($gpfrc->twin_radiator_vlv_fitted) && $gpfrc->twin_radiator_vlv_fitted == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_atervf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_atervf_no" name="twin_radiator_vlv_fitted" {{ (isset($gpfrc->twin_radiator_vlv_fitted) && $gpfrc->twin_radiator_vlv_fitted == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_atervf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>If so, are all radiators completely warm when boiler fired</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_isaarcwwbf_yes" name="completely_warm_on_fired" {{ (isset($gpfrc->completely_warm_on_fired) && $gpfrc->completely_warm_on_fired == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_isaarcwwbf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_isaarcwwbf_no" name="completely_warm_on_fired" {{ (isset($gpfrc->completely_warm_on_fired) && $gpfrc->completely_warm_on_fired == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_isaarcwwbf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">If single pipe system</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Is there circulation (heat) to all radiators</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_itchtar_yes" name="circulation_for_all_readiators" {{ (isset($gpfrc->circulation_for_all_readiators) && $gpfrc->circulation_for_all_readiators == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_itchtar_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_itchtar_no" name="circulation_for_all_readiators" {{ (isset($gpfrc->circulation_for_all_readiators) && $gpfrc->circulation_for_all_readiators == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_itchtar_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">If elderly steel pipework</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Is system sufficiently sound to power flush</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_issstpf_yes" name="suffifiently_sound" {{ (isset($gpfrc->suffifiently_sound) && $gpfrc->suffifiently_sound == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_issstpf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_issstpf_no" name="suffifiently_sound" {{ (isset($gpfrc->suffifiently_sound) && $gpfrc->suffifiently_sound == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_issstpf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
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
                        <x-base.form-label>Are they getting warm</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_atgw_yes" name="getting_warm" {{ (isset($gpfrc->getting_warm) && $gpfrc->getting_warm == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_atgw_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_atgw_no" name="getting_warm" {{ (isset($gpfrc->getting_warm) && $gpfrc->getting_warm == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_atgw_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Are TRV's fitted</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_atrvf_yes" name="are_trvs_fitted" {{ (isset($gpfrc->are_trvs_fitted) && $gpfrc->are_trvs_fitted == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_atrvf_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_atrvf_no" name="are_trvs_fitted" {{ (isset($gpfrc->are_trvs_fitted) && $gpfrc->are_trvs_fitted == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_atrvf_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Any obvious signs of neglect/leak</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_aosonl_yes" name="sign_of_neglect" {{ (isset($gpfrc->sign_of_neglect) && $gpfrc->sign_of_neglect == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_aosonl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_aosonl_no" name="sign_of_neglect" {{ (isset($gpfrc->sign_of_neglect) && $gpfrc->sign_of_neglect == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_aosonl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Do all thermostic radiator valves (TRV's) open fully</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_datrvof_yes" name="radiator_open_fully" {{ (isset($gpfrc->radiator_open_fully) && $gpfrc->radiator_open_fully == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_datrvof_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_datrvof_no" name="radiator_open_fully" {{ (isset($gpfrc->radiator_open_fully) && $gpfrc->radiator_open_fully == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_datrvof_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
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
                        <x-base.form-label>Location</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_atzvl_yes" name="valves_located" {{ (isset($gpfrc->valves_located) && $gpfrc->valves_located == 'Airing Cupboard' ? 'Checked' : '') }} value="Airing Cupboard" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_atzvl_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    Airing Cupboard
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_atzvl_no" name="valves_located" {{ (isset($gpfrc->valves_located) && $gpfrc->valves_located == 'Elsewhere' ? 'Checked' : '') }} value="Elsewhere" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_atzvl_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    Elsewhere
                                </x-base.button>
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
                        <x-base.form-label>Checked</x-base.form-label>
                        <div class="flex justify-start items-center">
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_fetnkck_yes" name="fe_tank_checked" {{ (isset($gpfrc->fe_tank_checked) && $gpfrc->fe_tank_checked == 'Yes' ? 'Checked' : '') }} value="Yes" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_fetnkck_yes" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="success">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    YES
                                </x-base.button>
                            </div>
                            <div class="radioItem mr-[3px]">
                                <input id="gwn_fetnkck_no" name="fe_tank_checked" {{ (isset($gpfrc->fe_tank_checked) && $gpfrc->fe_tank_checked == 'No' ? 'Checked' : '') }} value="No" type="radio" class="absolute w-0 h-0 opacity-0" />
                                <x-base.button as="label" for="gwn_fetnkck_no" size="sm" class="text-white text-sm px-3 py-1 text-[12px] rounded-[3px]" variant="danger">
                                    <x-base.lucide class="h-3 w-3 mr-1.5 checked" icon="check-circle"/><x-base.lucide class="h-3 w-3 mr-1.5 unchecked" icon="x-circle"/>
                                    NO
                                </x-base.button>
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
                        <x-base.table bordered sm id="invoiceItemsTable">
                            <x-base.table.thead class="max-sm:hidden">
                                <x-base.table.tr>
                                    <x-base.table.th class="whitespace-normal text-left">Test Parameter</x-base.table.th>
                                    <x-base.table.th class="whitespace-normal text-left">pH</x-base.table.th>
                                    <x-base.table.th class="whitespace-normal text-left">chloride (ppm)</x-base.table.th>
                                    <x-base.table.th class="whitespace-normal text-left">Hardness</x-base.table.th>
                                    <x-base.table.th class="whitespace-normal text-left">Inhibitor (ppm molybdate)</x-base.table.th>
                                </x-base.table.tr>
                            </x-base.table.thead>
                            <x-base.table.tbody>
                                <x-base.table.tr>
                                    <x-base.table.th class="whitespace-normal text-left">Mains water</x-base.table.th>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->mw_ph) ? $gpfrc->mw_ph : '') }}" name="mw_ph" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->mw_chloride) ? $gpfrc->mw_chloride : '') }}" name="mw_chloride" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->mw_hardness) ? $gpfrc->mw_hardness : '') }}" name="mw_hardness" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->mw_inhibitor) ? $gpfrc->mw_inhibitor : '') }}" name="mw_inhibitor" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                </x-base.table.tr>
                                <x-base.table.tr>
                                    <x-base.table.th class="whitespace-normal text-left">System water before PowerFlush</x-base.table.th>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->bpf_ph) ? $gpfrc->bpf_ph : '') }}" name="bpf_ph" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->bpf_chloride) ? $gpfrc->bpf_chloride : '') }}" name="bpf_chloride" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->bpf_hardness) ? $gpfrc->bpf_hardness : '') }}" name="bpf_hardness" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->bpf_inhibitor) ? $gpfrc->bpf_inhibitor : '') }}" name="bpf_inhibitor" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                </x-base.table.tr>
                                <x-base.table.tr>
                                    <x-base.table.th class="whitespace-normal text-left">System water after PowerFlush</x-base.table.th>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->apf_ph) ? $gpfrc->apf_ph : '') }}" name="apf_ph" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->apf_chloride) ? $gpfrc->apf_chloride : '') }}" name="apf_chloride" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->apf_hardness) ? $gpfrc->apf_hardness : '') }}" name="apf_hardness" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                    <x-base.table.td class="whitespace-normal text-left"><x-base.form-input value="{{ (isset($gpfrc->apf_inhibitor) ? $gpfrc->apf_inhibitor : '') }}" name="apf_inhibitor" class="w-full h-[30px] rounded-[3px]" type="text" placeholder="" /></x-base.table.td>
                                </x-base.table.tr>
                            </x-base.table.tbody>
                        </x-base.table>
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