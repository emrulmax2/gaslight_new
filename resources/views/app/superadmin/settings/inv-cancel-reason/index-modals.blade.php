<!-- BEGIN: Modal Content -->
<x-base.dialog id="addInvCancelReasonModal" staticBackdrop>
    <x-base.dialog.panel>
        <form method="post" action="#" id="addInvCancelReasonForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add Reason</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description>
                <div>
                    <x-base.form-label for="name">Name<span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="name" id="name" class="w-full" type="text" autocomplete="off" />
                    <div class="acc__input-error error-name text-danger text-xs mt-1"></div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="float-left mt-2">
                    <x-base.form-check>
                        <div data-tw-merge class="flex items-center">
                            <label data-tw-merge for="active" class="cursor-pointer mr-5">Is Active</label>
                            <x-base.form-switch.input checked="1" class="" id="active" name="active" value="1" type="checkbox" />
                        </div>
                    </x-base.form-check>
                </div>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveICRBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->
 
<!-- BEGIN: Modal Content -->
<x-base.dialog id="editInvCancelReasonModal" staticBackdrop>
    <x-base.dialog.panel>
        <form method="post" action="#" id="editInvCancelReasonForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Update Reason</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description>
                <div>
                    <x-base.form-label for="edit_name">Name<span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="name" id="edit_name" class="w-full" type="text" autocomplete="off" />
                    <div class="acc__input-error error-name text-danger text-xs mt-1"></div>
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
                <x-base.button class="w-auto" id="editICRBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Update
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="0"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->