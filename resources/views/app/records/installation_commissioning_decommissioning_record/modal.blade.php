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
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Work Type</x-base.form-label>
                            <div class="bg-white">
                                @if($worktype->count() > 0)
                                    @foreach($worktype as $wt)
                                        <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                            <x-base.form-check.label class="font-medium ml-0 block w-full" for="work_type_{{ $wt->id }}">{{ $wt->name }}</x-base.form-check.label>
                                            <x-base.form-check.input id="work_type_{{ $wt->id }}" name="work_type[]" class="absolute right-2 top-0 bottom-0 my-auto" type="checkbox" value="{{ $wt->id }}"/>
                                        </x-base.form-check>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Details description for work carried out</x-base.form-label>
                        <x-base.form-textarea name="details_work_carried_out" class="w-full h-[70px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Details of additional work required</x-base.form-label>
                        <x-base.form-textarea name="details_work_required" class="w-full h-[70px] rounded-[3px]" placeholder="Details"></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Is the gas installation/appliance(s) safe to use</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_itgiastu_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_itgiastu_yes" name="is_safe_to_use" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_itgiastu_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_itgiastu_no" name="is_safe_to_use" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_itgiastu_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_itgiastu_na" name="is_safe_to_use" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Have warning labels been affixed?</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hwlbaf_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hwlbaf_yes" name="have_labels_affixed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hwlbaf_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hwlbaf_no" name="have_labels_affixed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_hwlbaf_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_hwlbaf_na" name="have_labels_affixed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
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