<!-- BEGIN: Appliance Modal Content -->
<x-base.dialog id="applianceSystemModal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="applianceSystemForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Unvented Hot Water System</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Type</x-base.form-label>
                        <x-base.form-input value="" name="type" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Make</x-base.form-label>
                        <x-base.form-input value="" name="make" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Model</x-base.form-label>
                        <x-base.form-input value="" name="model" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Location</x-base.form-label>
                        <x-base.form-input value="" name="location" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Serial Number</x-base.form-label>
                        <x-base.form-input value="{{ (isset($guhwcrs->serial_no) && !empty($guhwcrs->serial_no) ? $guhwcrs->serial_no : '') }}" name="serial_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">GC Number</x-base.form-label>
                        <x-base.form-input value="{{ (isset($guhwcrs->gc_number) && !empty($guhwcrs->gc_number) ? $guhwcrs->gc_number : '') }}" name="gc_number" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    
                    
                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Inspection Details</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Indirect or Direct</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_inddir_yes">Indirect</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_inddir_yes" name="direct_or_indirect" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Indirect"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_inddir_no">Direct</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_inddir_no" name="direct_or_indirect" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Direct"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Gas boiler and/or solar, or Immersion heaters</x-base.form-label>
                        <x-base.form-input value="" name="boiler_solar_immersion" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Capacity (Ltrs)</x-base.form-label>
                        <x-base.form-input value="" name="capacity" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Makers warning labels attached</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mwlatch_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mwlatch_yes" name="warning_label_attached" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mwlatch_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mwlatch_no" name="warning_label_attached" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_mwlatch_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_mwlatch_na" name="warning_label_attached" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Inlet Water Pressure</x-base.form-label>
                        <x-base.form-input value="" name="water_pressure" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Flow rate</x-base.form-label>
                        <x-base.form-input value="" name="flow_rate" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Fully commissioned</x-base.form-label>
                        <x-base.form-input value="" name="fully_commissioned" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveSystemBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Appliance Modal Content -->
 
<!-- BEGIN: Inspection Record Modal Content -->
<x-base.dialog id="applianceInspectionModal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="applianceInspectionForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Inspection Record</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>System operating pressure (bar)</x-base.form-label>
                        <x-base.form-input value="" name="system_opt_pressure" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Operating pressure of expansion vassel (bar)</x-base.form-label>
                        <x-base.form-input value="" name="opt_presure_exp_vsl" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Operating pressure of expansion valve (bar)</x-base.form-label>
                        <x-base.form-input value="" name="opt_presure_exp_vlv" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Operating temperature of temperature relief valve (C)</x-base.form-label>
                        <x-base.form-input value="" name="tem_relief_vlv" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Operating temperature (C)</x-base.form-label>
                        <x-base.form-input value="" name="opt_temperature" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Pressure of combined temperature and pressure of relief valve (bar)</x-base.form-label>
                        <x-base.form-input value="" name="combined_temp_presr" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Maximum primary circuit pressure (bar)</x-base.form-label>
                        <x-base.form-input value="" name="max_circuit_presr" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Flow temperature (indirectly heated vassel) (C)</x-base.form-label>
                        <x-base.form-input value="" name="flow_temp" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    
                    
                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Discharge Pipework (D1) -  relief valve of tundish</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Normal size of D1 (mm)</x-base.form-label>
                        <x-base.form-input value="" name="d1_mormal_size" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Length of D1 (mm)</x-base.form-label>
                        <x-base.form-input value="" name="d1_length" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Number of discharges</x-base.form-label>
                        <x-base.form-input value="" name="d1_discharges_no" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Size of manifold, if more than one discharge</x-base.form-label>
                        <x-base.form-input value="" name="d1_manifold_size" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is tundish installed within the same location as the hot water storage vassel</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d1itiwtsl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d1itiwtsl_yes" name="d1_is_tundish_install_same_location" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d1itiwtsl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d1itiwtsl_no" name="d1_is_tundish_install_same_location" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is the tundish visible</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d1ittv_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d1ittv_yes" name="d1_is_tundish_visible" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d1ittv_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d1ittv_no" name="d1_is_tundish_visible" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is automatic means of identifying discharge installed</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d1iamoidi_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d1iamoidi_yes" name="d1_is_auto_dis_intall" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d1iamoidi_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d1iamoidi_no" name="d1_is_auto_dis_intall" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <h2 class="mb-4 mt-4 font-medium text-base leading-none tracking-normal">Discharge Pipework (D2) -  tundish to point of termination</h2>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Norminal size of D2 (mm)</x-base.form-label>
                        <x-base.form-input value="" name="d2_mormal_size" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label>Pipework Material</x-base.form-label>
                        <x-base.form-input value="" name="d2_pipework_material" class="w-full h-[35px] rounded-[3px]" type="text" placeholder="" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Does pipework have a minimum vertical length of 300mm from tundish</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2dphamvloft_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2dphamvloft_yes" name="d2_minimum_v_length" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2dphamvloft_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2dphamvloft_no" name="d2_minimum_v_length" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Does the pipework fall continuously to point of termination</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2dtpfctpot_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2dtpfctpot_yes" name="d2_fall_continuously" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2dtpfctpot_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2dtpfctpot_no" name="d2_fall_continuously" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Method of termination</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2mot_yes">Gully</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2mot_yes" name="d2_termination_method" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Gully"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2mot_no">Low Level</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2mot_no" name="d2_termination_method" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Low Level"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2mot_na">Soil Stack</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2mot_na" name="d2_termination_method" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Soil Stack"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2mot_naa">High Level</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2mot_naa" name="d2_termination_method" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="High Level"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Method of termination satisfactory</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2mots_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2mots_yes" name="d2_termination_satisfactory" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_d2mots_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_d2mots_no" name="d2_termination_satisfactory" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label class="mb-1">Comments</x-base.form-label>
                        <x-base.form-textarea name="comments" class="w-full h-[60px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveInspectionBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Inspection Record Modal Content -->