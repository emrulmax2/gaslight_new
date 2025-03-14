<!-- BEGIN: Add Modal Content -->
<x-base.dialog id="add-invoice-modal" size="xl" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">Line Item</h2>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12 sm:col-span-6">
                <x-base.form-label for="description">Description</x-base.form-label>
                <textarea name="add_description" id="description" class="w-full transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 " id="" rows="5"></textarea>
            </div>
            <div class="col-span-12 sm:col-span-6 flex gap-3">
                <div class="">
                    <x-base.form-label for="units">Units</x-base.form-label>
                    <x-base.form-input id="units" name="add_units" type="text" placeholder="units" />
                </div>
                <div>
                    <x-base.form-label for="price">Price</x-base.form-label>
                    <x-base.form-input id="price" name="add_price" type="text" placeholder="price" />
                </div>
                <div class="addInvoiceVatField">
                    <x-base.form-label for="vat">Vat</x-base.form-label>
                    <x-base.form-input id="vat" name="add_vat" type="text" placeholder="vat" value="20" />
                </div>
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer>
            <x-base.button class="mr-1 w-20 addInvoiceModalHide" type="button" variant="outline-secondary" >Cancel</x-base.button>
            <x-base.button class="w-20 AddInvoiceItemBtn" type="button" variant="primary" >Add</x-base.button>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Add Modal Content -->

<!-- BEGIN: Edit Modal Content -->
<x-base.dialog id="edit-invoice-modal" size="xl" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">Line Item</h2>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12 sm:col-span-6">
                <x-base.form-label for="description">Description</x-base.form-label>
                <textarea name="edit_description" id="description" class="w-full transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 " id="" rows="10"></textarea>
            </div>
            <div class="col-span-12 sm:col-span-6 flex gap-3">
                <div class="">
                    <x-base.form-label for="units">Units</x-base.form-label>
                    <x-base.form-input id="units" name="edit_units" type="text" placeholder="units" />
                </div>
                <div>
                    <x-base.form-label for="price">Price</x-base.form-label>
                    <x-base.form-input id="price" name="edit_price" type="text" placeholder="price" />
                </div>
                <div class="editInvoiceVatField">
                    <x-base.form-label for="vat">Vat</x-base.form-label>
                    <x-base.form-input id="vat" name="edit_vat" type="text" placeholder="vat" />
                </div>
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer>
           <div class="flex justify-between">
                <div>
                    <x-base.button class="mr-1 w-20 deleteInvoiceItemBtn" type="button" variant="danger">Delete</x-base.button>
                </div>
                <div>
                    <x-base.button class="mr-1 w-20 editInvoiceModalHide" type="button" variant="outline-secondary">Cancel</x-base.button>
                    <x-base.button class="w-20 updateInvoiceItemBtn" type="button" variant="primary" >Update</x-base.button>
                </div>
           </div>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Edit Modal Content -->

<!-- BEGIN: Pre Payment Modal Content -->
<x-base.dialog id="pre-payment-invoice-modal" size="md" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title class="justify-between">
            <div>
                <h2 class="mr-auto text-base font-medium">Add Pre-Payment</h2>
                <span>This invoice has $200 outstanding</span>
            </div>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12">
                <x-base.form-label for="pre_payment_amount">Payment Amount</x-base.form-label>
                <x-base.form-input step="any" name="pre_payment_amount" id="pre_payment_amount" class="w-full" type="number" placeholder="Payment amount" />
            </div>
            <div class="col-span-12">
                <x-base.form-label for="pre_payment_method">Payment Method</x-base.form-label>
                <x-base.tom-select class="w-full" id="pre_payment_method" name="pre_payment_method" data-placeholder="Please Select">
                    <option value="">Please Select</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                </x-base.tom-select>
            </div>
            <div class="col-span-12">
                <x-base.form-label for="pre_payment_date">Payment Date</x-base.form-label>
                <x-base.litepicker name="pre_payment_date" id="pre_payment_date" value="{{ isset($job->issued_date) ? date('d-m-Y', strtotime($job->issued_date)) : date('d-m-Y') }}" class="block" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off" />
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer>
            <x-base.button class="mr-1 w-20 prePaymentInvoiceModalHide" type="button" variant="outline-secondary" >
                Cancel
            </x-base.button>
            <x-base.button class="w-20 prePaymentModalRecordBtn" type="button" variant="primary" >
                Record
            </x-base.button>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Pre Payment Modal Content -->

<!-- BEGIN: Discount Modal Content -->
<x-base.dialog id="discount-invoice-modal" size="md" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title class="justify-between">
            <div>
                <h2 class="mr-auto text-base font-medium">Invoice Discount</h2>
                <span>This invoice has $200 outstanding</span>
            </div>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12">
                <x-base.form-label for="discount_amount">Discount Amount</x-base.form-label>
                <x-base.form-input step="any" name="discount_amount" id="discount_amount" class="w-full" type="number" placeholder="Discount amount" />
            </div>
            <div class="col-span-12 discountVatField">
                <x-base.form-label for="discount_vat_rate">Vat Rate %</x-base.form-label>
                <x-base.form-input step="any" name="discount_vat_rate" id="discount_vat_rate" class="w-full" type="number" placeholder="Vat Rate %" />
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer>
            <x-base.button class="mr-1 w-20 discountInvoiceModalHide" type="button" variant="outline-secondary" >
                Cancel
            </x-base.button>
            <x-base.button class="discountModalRecordBtn" type="button" variant="primary" >
                Record
            </x-base.button>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Discount Modal Content -->