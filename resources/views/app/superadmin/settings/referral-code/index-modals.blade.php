<!-- BEGIN: Modal Content -->
<x-base.dialog id="addReferralCodeModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="addReferralCodeForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add Referral Code</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="code">Code<span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="code" id="code" class="w-full" type="text" autocomplete="off" />
                    <div class="acc__input-error error-code text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="no_of_days">No Of Days <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="no_of_days" id="no_of_days" class="w-full" type="number" step="any" />
                    <div class="acc__input-error error-no_of_days text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="expiry_date">Expiry Date</x-base.form-label>
                    <x-base.form-input type="text" name="expiry_date" id="expiry_date" class="w-full z-10" autocomplete="off" />
                    <div class="acc__input-error error-expiry_date text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="max_no_of_use">Max No Of Uses</x-base.form-label>
                    <x-base.form-input name="max_no_of_use" id="max_no_of_use" class="w-full" type="number" step="any" />
                    <div class="acc__input-error error-max_no_of_use text-danger text-xs mt-1"></div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="float-left mt-2">
                    <x-base.form-check>
                        <div data-tw-merge class="flex items-center">
                            <label data-tw-merge for="active" class="cursor-pointer mr-5">Is Active</label>
                            <x-base.form-switch.input class="" id="active" name="active" value="1" type="checkbox" />
                        </div>
                    </x-base.form-check>
                </div>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="savePackBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Add Referral Code
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->
 
<!-- BEGIN: Modal Content -->
<x-base.dialog id="editReferralCodeModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="editReferralCodeForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Update Referral Code</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="no_of_days">No Of Days <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="no_of_days" id="no_of_days" class="w-full" type="number" step="any" />
                    <div class="acc__input-error error-no_of_days text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="expiry_date">Expiry Date</x-base.form-label>
                    <x-base.form-input type="text" name="expiry_date" id="edit_expiry_date" class="w-full z-10" autocomplete="off" />
                    <div class="acc__input-error error-expiry_date text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label for="max_no_of_use">Max No Of Uses</x-base.form-label>
                    <x-base.form-input name="max_no_of_use" id="max_no_of_use" class="w-full" type="number" step="any" />
                    <div class="acc__input-error error-max_no_of_use text-danger text-xs mt-1"></div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="float-left mt-2">
                    <x-base.form-check>
                        <div data-tw-merge class="flex items-center">
                            <label data-tw-merge for="edit_active" class="cursor-pointer mr-5">Is Active</label>
                            <x-base.form-switch.input class="" id="edit_active" name="active" value="1" type="checkbox" />
                        </div>
                    </x-base.form-check>
                </div>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="editPackBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Update Referral Code
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="0"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->