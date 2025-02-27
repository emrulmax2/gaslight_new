<!-- BEGIN: Modal Content -->
<x-base.dialog id="editCustomerPropertyModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="updatePropertyForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Edit Job Address</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <x-base.form-input name="customer_id" class="w-full" type="hidden" value="{{ $customer->id }}" />
                <x-base.form-input name="property_id" id="property_id" class="w-full" type="hidden"  />
                <div class="col-span-12 sm:col-span-6">
                    <h2 class="text-base font-medium mb-2">Job Address Details</h2>
                    <div class="theAddressWrap" id="jobAddressWrap">
                        <div class="mb-3">
                            <x-base.button class="w-full coptyCustomerAddress text-primary" data-tw-dismiss="modal" type="button" variant="secondary" >
                                <x-base.lucide class="mr-2 h-4 w-4" icon="copy" /> Copy Customer Address 
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#164e63e6" icon="oval" />
                            </x-base.button>
                        </div>
                        <div class="mb-3">
                            <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="customer_address_line_1">Address Line 1</x-base.form-label>
                            <x-base.form-input name="address_line_1" id="address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
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
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <h2 class="text-base font-medium mb-2">Occupant's Details</h2>
                    <div class="mb-3">
                        <x-base.form-label for="occupant_name">Name</x-base.form-label>
                        <x-base.form-input name="occupant_name" id="occupant_name" class="w-full" type="text" placeholder="Name" />
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="occupant_phone">Phone</x-base.form-label>
                        <x-base.form-input name="occupant_phone" id="occupant_phone" class="w-full" type="text" placeholder="Phone" />
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="occupant_email">Email</x-base.form-label>
                        <x-base.form-input name="occupant_email" id="occupant_email" class="w-full" type="email" placeholder="Email Address" />
                    </div>
                    <h2 class="text-base font-medium mb-2 pt-5">Gas Service Due Date</h2>
                    <div class="mb-3">
                        <x-base.litepicker name="due_date" id="due_date" class="block w-full" data-single-mode="true" data-format="DD-MM-YYYY" />
                    </div>
                </div>
                <div class="col-span-12">
                    <h2 class="text-base font-medium mb-2">Note</h2>
                    <x-base.form-textarea name="note" id="note" class="w-full h-[80px]" placeholder="Note..."></x-base.form-textarea>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updatePropertyBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Update Address
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->
 
<!-- BEGIN: Modal Content -->
<x-base.dialog id="addCustomerPropertyModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="propertyCreateForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Create Job Address</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <x-base.form-input name="customer_id" class="w-full" type="hidden" value="{{ $customer->id }}" />
                <div class="col-span-12 sm:col-span-6">
                    <h2 class="text-base font-medium mb-2">Job Address Details</h2>
                    <div class="theAddressWrap" id="jobAddressWrap">
                        <div class="mb-3">
                            <x-base.button class="w-full coptyCustomerAddress text-primary" data-tw-dismiss="modal" type="button" variant="secondary" >
                                <x-base.lucide class="mr-2 h-4 w-4" icon="copy" /> Copy Customer Address 
                                <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#164e63e6" icon="oval" />
                            </x-base.button>
                        </div>
                        <div class="mb-3">
                            <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="customer_address_line_1">Address Line 1</x-base.form-label>
                            <x-base.form-input name="address_line_1" id="address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
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
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <h2 class="text-base font-medium mb-2">Occupant's Details</h2>
                    <div class="mb-3">
                        <x-base.form-label for="occupant_name">Name</x-base.form-label>
                        <x-base.form-input name="occupant_name" id="occupant_name" class="w-full" type="text" placeholder="Name" />
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="occupant_phone">Phone</x-base.form-label>
                        <x-base.form-input name="occupant_phone" id="occupant_phone" class="w-full" type="text" placeholder="Phone" />
                    </div>
                    <div class="mb-3">
                        <x-base.form-label for="occupant_email">Email</x-base.form-label>
                        <x-base.form-input name="occupant_email" id="occupant_email" class="w-full" type="email" placeholder="Email Address" />
                    </div>
                    <h2 class="text-base font-medium mb-2 pt-5">Gas Service Due Date</h2>
                    <div class="mb-3">
                        <x-base.litepicker name="due_date" id="due_date" class="block w-full" data-single-mode="true" data-format="DD-MM-YYYY" />
                    </div>
                </div>
                <div class="col-span-12">
                    <h2 class="text-base font-medium mb-2">Note</h2>
                    <x-base.form-textarea name="note" id="note" class="w-full h-[80px]" placeholder="Note..."></x-base.form-textarea>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="propertySaveBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Add Address
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->