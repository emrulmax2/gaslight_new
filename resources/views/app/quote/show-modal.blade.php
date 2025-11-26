<!-- BEGIN: Approve & Send Email Content -->
<x-base.dialog id="addCustomerEmailModal" size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="addCustomerEmailForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Update Email & Send</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="p-5">
                <div class="col-span-12 vatWrap">
                    <x-base.form-label>Customer's Email ID<span class="text-danger ml-1">*</span></x-base.form-label>
                    <x-base.form-input name="customer_email" id="customer_email" class="w-full" type="email" />
                    <div class="acc__input-error error-customer_email text-danger text-xs mt-1" style="display: none;"></div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="sendMailBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Send
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
            <input type="hidden" name="quote_id" value="{{ $quote->id }}"/>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Approve & Send Email Modal Content -->