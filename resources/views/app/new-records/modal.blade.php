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
<x-base.dialog id="addJobAddressOccupantModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="addJobAddressOccupantForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add Occupant Details</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description>
                <x-base.form-input name="customer_property_id" class="w-full" type="hidden" value="0" />
                <h2 class="text-base font-medium mb-2">Occupant's Details</h2>
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

<!-- BEGIN: Relation Modal Content -->
<x-base.dialog id="relationModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="relationForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Relation</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="p-0">
                @if($relations->count() > 0)
                    @foreach($relations as $rel)
                        <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 relative border-b">
                            <x-base.form-check.label class="font-medium ml-0 block w-full py-2" for="relation_{{ $rel->id}}">{{ $rel->name }}</x-base.form-check.label>
                            <x-base.form-check.input id="relation_{{ $rel->id}}" name="relation_item" class="relation_item absolute right-2 top-0 bottom-0 my-auto" type="radio" data-label="{{ $rel->name }}" value="{{ $rel->id }}"/>
                        </x-base.form-check>
                    @endforeach
                @endif
            </x-base.dialog.description>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Relation Modal Content -->