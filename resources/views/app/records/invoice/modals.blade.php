<!-- BEGIN: Add Modal Content -->
<x-base.dialog id="addInvoiceItemModal" size="xl" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">Line Item</h2>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="modal-body grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12 sm:col-span-6">
                <x-base.form-label>Description</x-base.form-label>
                <textarea name="description" class="w-full transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 " id="" rows="5"></textarea>
            </div>
            <div class="col-span-12 sm:col-span-6 flex gap-3">
                <div class="">
                    <x-base.form-label>Units</x-base.form-label>
                    <x-base.form-input name="units" type="number" min="1" step="1" placeholder="1" />
                </div>
                <div>
                    <x-base.form-label>Price</x-base.form-label>
                    <x-base.form-input name="price" type="number" step="any" min="0" placeholder="0.00" />
                </div>
                <div class="vatWrap">
                    <x-base.form-label>VAT %</x-base.form-label>
                    <x-base.form-input name="vat" type="number" placeholder="20" value="0" />
                </div>
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer>
            <x-base.button class="mr-1 w-auto text-white" data-tw-dismiss="modal" type="button" variant="pending" >Cancel</x-base.button>
            <x-base.button class="w-auto text-white" id="addInvoiceItemBtn" type="button" variant="success" >
                Add Item
                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
            </x-base.button>
            <input type="hidden" name="srial" value="0"/>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Add Modal Content -->

<!-- BEGIN: Edit Modal Content -->
<x-base.dialog id="editInvoiceItemModal" size="xl" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title>
            <h2 class="mr-auto text-base font-medium">Line Item</h2>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="modal-body grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12 sm:col-span-6">
                <x-base.form-label>Description</x-base.form-label>
                <textarea name="description" class="w-full transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 " id="" rows="5"></textarea>
            </div>
            <div class="col-span-12 sm:col-span-6 flex gap-3">
                <div class="">
                    <x-base.form-label>Units</x-base.form-label>
                    <x-base.form-input name="units" type="number" min="1" step="1" placeholder="1" />
                </div>
                <div>
                    <x-base.form-label>Price</x-base.form-label>
                    <x-base.form-input name="price" type="number" step="any" min="0" placeholder="0.00" />
                </div>
                <div class="vatWrap">
                    <x-base.form-label>VAT %</x-base.form-label>
                    <x-base.form-input name="vat" type="number" placeholder="20" value="0" />
                </div>
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer>
           <div class="flex justify-between">
                <div>
                    <x-base.button class="mr-1 w-20 deleteInvoiceItemBtn" type="button" variant="danger">Delete</x-base.button>
                </div>
                <div>
                    <x-base.button class="mr-1 w-auto text-white"  data-tw-dismiss="modal" type="button" variant="pending">Cancel</x-base.button>
                    <x-base.button class="w-auto text-white" id="updateInvoiceItemBtn" type="button" variant="success" >
                        Update
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <input type="hidden" name="srial" value="0"/>
                </div>
           </div>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Edit Modal Content -->

<!-- BEGIN: Pre Payment Modal Content -->
<x-base.dialog id="prePaymentInvoiceModal" size="md" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title class="justify-between">
            <div>
                <h2 class="mr-auto text-base font-medium">Add Pre-Payment</h2>
                <span class="dueLeft">This invoice has £0 outstanding</span>
            </div>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="modal-body grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12">
                <x-base.form-label>Payment Amount</x-base.form-label>
                <x-base.form-input step="any" name="advance_amount" class="w-full" type="number" placeholder="0.0" />
            </div>
            <div class="col-span-12">
                <x-base.form-label>Payment Method</x-base.form-label>
                <x-base.form-select class="w-full tom-select py-0" id="payment_method_id" name="payment_method_id" data-placeholder="Please Select">
                    <option value="">Please Select</option>
                    @if($methods->count() > 0)
                        @foreach($methods as $option)
                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                        @endforeach
                    @endif
                </x-base.form-select>
            </div>
            <div class="col-span-12">
                <x-base.form-label>Payment Date</x-base.form-label>
                <x-base.litepicker name="advance_pay_date" value="{{ date('d-m-Y') }}" class="block" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off" />
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer class="flex">
            <div>
                <x-base.button style="display: none;" class="mr-1 w-auto" id="removeAdvanceBtn" type="button" variant="danger">Remove</x-base.button>
            </div>
            <div class="text-right ml-auto">
                <x-base.button as="a" href="javascript:void(0);" class="mr-1 w-auto text-white" data-tw-dismiss="modal" type="button" variant="pending" >
                    Cancel
                </x-base.button>
                <x-base.button class="w-auto text-white" id="addAdvancePayBtn" type="button" variant="success" >
                    Add Record
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="max_advance" value="0"/>
            </div>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Pre Payment Modal Content -->

<!-- BEGIN: Discount Modal Content -->
<x-base.dialog id="invoiceDiscountModal" size="md" class="max-w-full">
    <x-base.dialog.panel>
        <x-base.dialog.title class="justify-between">
            <div>
                <h2 class="mr-auto text-base font-medium">Invoice Discount</h2>
                <span class="dueLeft">This invoice has £0 outstanding</span>
            </div>
            <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
        </x-base.dialog.title>
        <x-base.dialog.description class="modal-body grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12">
                <x-base.form-label>Discount Amount</x-base.form-label>
                <x-base.form-input step="any" min="1" name="discount_amount" class="w-full" type="number" placeholder="0.0" />
            </div>
            <div class="col-span-12 discountVatField">
                <x-base.form-label>VAT %</x-base.form-label>
                <x-base.form-input step="any" name="discount_vat_rate" class="w-full" type="number" placeholder="20%" />
            </div>
        </x-base.dialog.description>
        <x-base.dialog.footer class="flex">
            <div>
                <x-base.button style="display: none;" class="mr-1 w-auto" id="removeDiscountBtn" type="button" variant="danger">Remove</x-base.button>
            </div>
            <div class="text-right ml-auto">
                <x-base.button class="mr-1 w-auto text-white" data-tw-dismiss="modal" type="button" variant="pending" >
                    Cancel
                </x-base.button>
                <x-base.button class="w-auto text-white" id="addDiscountModalBtn" type="button" variant="success" >
                    Add Discount
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="max_discount" value="0"/>
            </div>
        </x-base.dialog.footer>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Discount Modal Content -->