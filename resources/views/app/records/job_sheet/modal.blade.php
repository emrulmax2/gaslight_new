<!-- BEGIN: Appliance Modal Content -->
<x-base.dialog id="applianceModal" staticBackdrop size="xl">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="applianceForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Job Sheet Details</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12">
                        <x-base.form-label>Date</x-base.form-label>
                        <x-base.litepicker value="" name="date" class="w-full h-[35px] rounded-[3px]" data-format="DD-MM-YYYY" data-single-mode="true" />
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Job Notes</x-base.form-label>
                        <x-base.form-textarea name="job_note" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Spares Required</x-base.form-label>
                        <x-base.form-textarea name="spares_required" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Job Ref</x-base.form-label>
                        <x-base.form-textarea name="job_ref" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Arrival Time</x-base.form-label>
                        <x-base.form-textarea name="arrival_time" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Departure Time</x-base.form-label>
                        <x-base.form-textarea name="departure_time" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Hours Used</x-base.form-label>
                        <x-base.form-textarea name="hours_used" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label class="mb-1">Awaiting Parts</x-base.form-label>
                        <x-base.form-textarea name="awaiting_parts" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <div class="bg-slate-100 p-2">
                            <x-base.form-label class="mb-2 mt-1 block font-medium">Job Completed</x-base.form-label>
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_jbcmpl_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_jbcmpl_yes" name="job_completed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Yes"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_jbcmpl_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_jbcmpl_no" name="job_completed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="No"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-2 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="gwn_jbcmpl_na">N/A</x-base.form-check.label>
                                    <x-base.form-check.input id="gwn_jbcmpl_na" name="job_completed" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="N/A"/>
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