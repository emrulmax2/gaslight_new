<!-- BEGIN: Job Linked Modal Content -->
<x-base.dialog id="linkedJobModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="linkedJobForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Linked Job</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="bg-slate-200">
                <div class="relative mb-2">
                    <x-base.form-input name="search_job" id="search_job" class="w-full border-none rounded-none m-0" type="text" placeholder="search Here" />
                    <x-base.lucide class="h-4 w-4 absolute right-2 top-0 bottom-0 m-auto text-slate-400" icon="search" />
                </div>
                <div class="linkedJobListWrap overflow-x-hidden overflow-y-auto" style="max-height: 75vh;">
                    
                </div>
            </x-base.dialog.description>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Job Linked Modal Content -->

 <!-- BEGIN: Job Linked Modal Content -->
<x-base.dialog id="customerListModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="customerListForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Customers</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="bg-slate-200 py-2">
                <div class="relative mb-2">
                    <a href="{{ route('customers.create') }}" data-key="record_url" data-value="{{ url()->current() }}"class="theStorageTrigger text-primary flex justify-between items-center p-3 bg-white mt-2 text-base">Add Customer<x-base.lucide class="ml-auto h-4 w-4" icon="plus-circle" /></a>
                </div>
                <div class="relative customerSearchWrap mb-2">
                    <x-base.form-input name="search_customer" id="search_customer" class="w-full border-none rounded-none m-0" type="text" placeholder="search Here" />
                    <x-base.lucide class="h-4 w-4 absolute right-2 top-0 bottom-0 m-auto text-slate-400" icon="search" />
                </div>
                <div class="customersListWrap overflow-x-hidden overflow-y-auto" style="max-height: 75vh;">
                    
                </div>
            </x-base.dialog.description>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Job Linked Modal Content -->

<!-- BEGIN: Job Address Modal Content -->
<x-base.dialog id="customerJobAddressModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="customerJobAddressForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Customer Job Addresses</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="bg-slate-200">
                <a href="javascript:void(0);" data-customer-id="0" class="addJobAddressBtn text-primary flex justify-between items-center p-3 bg-white mb-2 text-base">Add New Address<x-base.lucide class="ml-auto h-4 w-4" icon="plus-circle" /></a>
                <div class="customerJobAddressWrap">
                    
                </div>
            </x-base.dialog.description>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Job Address Modal Content -->

<!-- BEGIN: Modal Content -->
<x-base.dialog id="addJobAddressModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="addJobAddressForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Create Job Address</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description>
                <x-base.form-input name="customer_id" class="w-full" type="hidden" value="0" />
                <div class="theAddressWrap" id="jobAddressWrap">
                    <div class="mb-3">
                        <x-base.form-label for="customer_address_line_1">Address Lookup</x-base.form-label>
                        <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="customer_address_line_1">Address Line 1</x-base.form-label>
                        <x-base.form-input name="address_line_1" id="customer_address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
                        <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="address_line_2">Address Line 2</x-base.form-label>
                        <x-base.form-input name="address_line_2" id="address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2 (Optional)" />
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="city">Town/City</x-base.form-label>
                        <x-base.form-input name="city" id="city" class="w-full city" type="text" placeholder="Town/City" />
                        <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="state">Region/County</x-base.form-label>
                        <x-base.form-input name="state" id="state" class="w-full state" type="text" placeholder="Region/County" />
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="postal_code">Post Code</x-base.form-label>
                        <x-base.form-input name="postal_code" id="postal_code" class="w-full postal_code" type="text" placeholder="Post Code" />
                        <div class="acc__input-error error-postal_code text-danger text-xs mt-1"></div>
                    </div>
                    <x-base.form-input name="country" id="country" class="w-full country" type="hidden" value="" />
                    <x-base.form-input name="latitude" class="w-full latitude" type="hidden" value="" />
                    <x-base.form-input name="longitude" class="w-full longitude" type="hidden" value="" />
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="addressSaveBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Add Address
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->

<!-- BEGIN: Occupant Modal Content -->
<x-base.dialog id="jobAddressOccupantModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="jobAddressOccupantForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Job Addresses Occupant</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="bg-slate-200">
                <a href="javascript:void(0);" data-customer-property-id="0" class="addJobAddressOccupantBtn text-primary flex justify-between items-center p-3 bg-white mb-2 text-base">Add Occupant Details<x-base.lucide class="ml-auto h-4 w-4" icon="plus-circle" /></a>
                <div class="customerJobAddressOccupantWrap">
                    
                </div>
            </x-base.dialog.description>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Occupant Modal Content -->

<!-- BEGIN: Modal Content -->
<x-base.dialog id="addJobAddressOccupantModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="addJobAddressOccupantForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add Occupant Details</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description>
                <x-base.form-input name="customer_property_id" class="w-full" type="hidden" value="0" />
                <div class="mb-3">
                    <x-base.form-label for="occupant_name">Name <span class="text-danger">*</span></x-base.form-label>
                    <x-base.form-input name="occupant_name" id="occupant_name" class="w-full capitalize" type="text" placeholder="Name" />
                    <div class="acc__input-error error-occupant_name text-danger text-xs mt-1"></div>
                </div>
                <div class="mb-3">
                    <x-base.form-label for="occupant_phone">Phone</x-base.form-label>
                    <x-base.form-input name="occupant_phone" id="occupant_phone" class="w-full" type="text" placeholder="Phone" />
                    <div class="acc__input-error error-occupant_phone text-danger text-xs mt-1"></div>
                </div>
                <div class="mb-3">
                    <x-base.form-label for="occupant_email">Email</x-base.form-label>
                    <x-base.form-input name="occupant_email" id="occupant_email" class="w-full" type="email" placeholder="Email Address" />
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="occupantSaveBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Add Occupant
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->




<!-- BEGIN: Invoice Item Content -->
<x-base.dialog id="invoiceItemModal">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="invoiceItemForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Line Item</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="p-5 pt-3">
                <input type="hidden" name="inv_item_serial" value="0"/>
                <input type="hidden" name="edit" value="0"/>
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12">
                        <x-base.form-label class="mb-1">Description <span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.form-textarea name="description" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                        <div class="acc__input-error error-description text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Units <span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.form-input name="units" type="number" min="1" step="1" placeholder="1" class="h-[35px] rounded-[3px]" />
                        <div class="acc__input-error error-units text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label class="mb-1">Price <span class="text-danger ml-1">*</span></x-base.form-label>
                        <x-base.form-input name="price" type="number" step="any" min="0" placeholder="0.00" class="h-[35px] rounded-[3px]" />
                        <div class="acc__input-error error-units text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 vatWrap">
                        <x-base.form-label class="mb-1">VAT %</x-base.form-label>
                        <x-base.form-input name="vat" type="number" placeholder="20" value="20" class="h-[35px] rounded-[3px]" />
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer class="flex">
                <div>
                    <x-base.button style="display: none;" class="mr-auto w-[34px] h-[34px] px-0 py-0 justify-center items-center" id="removeItemBtn" type="button" variant="danger"><x-base.lucide class="h-4 w-4" icon="trash-2" /></x-base.button>
                </div>
                <div class="text-right ml-auto">
                    <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                    <x-base.button class="w-auto" id="saveItemBtn" type="submit" variant="primary">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Save Item
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                </div>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Invoice Item Modal Content -->


<!-- BEGIN: Discount Modal Content -->
<x-base.dialog id="invoiceDiscountModal" size="sm" class="max-w-full">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="invoiceDiscountForm">
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
                    <x-base.form-input step="any" min="1" name="amount" class="w-full" type="number" placeholder="0.0" />
                    <div class="acc__input-error error-amount text-danger text-xs mt-1"></div>
                </div>
                <!-- <div class="col-span-12 vatWrap">
                    <x-base.form-label>VAT %</x-base.form-label>
                    <x-base.form-input step="any" name="vat" class="w-full" type="number" placeholder="20%" />
                </div> -->
            </x-base.dialog.description>
            <x-base.dialog.footer class="flex">
                <div>
                    <x-base.button style="display: none;" class="mr-auto w-[34px] h-[34px] px-0 py-0 justify-center items-center" id="removeDiscountBtn" type="button" variant="danger"><x-base.lucide class="h-4 w-4" icon="trash-2" /></x-base.button>
                </div>
                <div class="text-right ml-auto">
                    <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                    <x-base.button class="w-auto" id="saveDiscountBtn" type="submit" variant="primary">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Save Discount
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <input type="hidden" name="max_discount" value="0"/>
                </div>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Discount Modal Content -->


<!-- BEGIN: Pre Payment Modal Content -->
<x-base.dialog id="invoiceAdvanceModal" size="md" class="max-w-full">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="invoiceAdvanceForm">
            <x-base.dialog.title class="justify-between">
                <div>
                    <h2 class="mr-auto text-base font-medium">Add Pre-Payment</h2>
                    <span class="dueLeft">This invoice has £0 outstanding</span>
                </div>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <x-base.form-label>Payment Date</x-base.form-label>
                    <x-base.litepicker name="advance_pay_date" value="{{ date('d-m-Y') }}" class="block" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off" />
                </div>
                <div class="col-span-12">
                    <x-base.form-label>Payment Amount</x-base.form-label>
                    <x-base.form-input step="any" name="advance_amount" class="w-full" type="number" placeholder="0.0" />
                    <div class="acc__input-error error-advance_amount text-danger text-xs mt-1"></div>
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
            </x-base.dialog.description>
            <x-base.dialog.footer class="flex">
                <div>
                    <x-base.button style="display: none;" class="mr-auto w-[34px] h-[34px] px-0 py-0 justify-center items-center" id="removeAdvanceBtn" type="button" variant="danger"><x-base.lucide class="h-4 w-4" icon="trash-2" /></x-base.button>
                </div>
                <div class="text-right ml-auto">
                    <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                    <x-base.button class="w-auto" id="addAdvancePayBtn" type="submit" variant="primary">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Save Advance
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <input type="hidden" name="max_advance" value="0"/>
                </div>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Pre Payment Modal Content -->

<!-- BEGIN: Invoice Note Content -->
<x-base.dialog id="invoiceNoteModal">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="invoiceNoteForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Note</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="javascript:void(0);" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="p-5">
                <x-base.form-textarea name="note" class="w-full h-[70px] rounded-[3px]" placeholder=""></x-base.form-textarea>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveNoteBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save Note
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Invoice Item Modal Content -->