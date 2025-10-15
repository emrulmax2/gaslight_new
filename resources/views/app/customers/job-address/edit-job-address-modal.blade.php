<!-- BEGIN: Update Modal Content -->
<x-base.dialog id="jobAddressNoteModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="jobAddressNoteForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Note</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <x-base.form-textarea name="fieldValue" id="fieldValue" class="w-full h-[120px]" placeholder="Note">{{ isset($property->note) ? $property->note : '' }}</x-base.form-textarea>
                <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateNoteBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $property->id }}"/>
                <input type="hidden" name="fieldName" value="note"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Modal Content -->
 
<!-- BEGIN: Update Modal Content -->
<x-base.dialog id="updatePropertyDataModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updatePropertyDataForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Data</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12">
                        <x-base.form-label><span class="fieldTitle">Value</span><span class="requiredLabel text-danger hidden ml-1">*</span></x-base.form-label>
                        <x-base.form-input type="text" value="" name="fieldValue" id="fieldValue" class="w-full h-[35px] rounded-[3px]" autocomplete="off" />
                        <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateDataBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $property->id }}"/>
                <input type="hidden" name="fieldName" value=""/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Modal Content -->
 
<!-- BEGIN: Update Due Date Modal Content -->
<x-base.dialog id="updatePropertyDueDateModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updatePropertyDueDateForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Due Date</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12">
                        <x-base.litepicker value="{{ !empty($property->due_date) ? date('d-m-Y', strtotime($property->due_date)) : '' }}" name="fieldValue" class="w-full h-[35px] rounded-[3px]" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                        <div class="acc__input-error error-fieldValue text-danger text-xs mt-1"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateDueDateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $property->id }}"/>
                <input type="hidden" name="fieldName" value="due_date"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Due Date Modal Content -->

<!-- BEGIN: Company Address Modal Content -->
<x-base.dialog id="propertyAddressModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="propertyAddressForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center">Update Address</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="theAddressWrap" id="customerAddressWrap">
                <div>
                    <x-base.form-label for="customer_address_lookup">Address Lookup</x-base.form-label>
                    <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                </div>
                <div class="mt-3">
                    <x-base.form-label for="address_line_1">Address Line 1 <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($property->address_line_1) ? $property->address_line_1 : '' }}" name="address_line_1" id="address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
                    <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="address_line_2">Address Line 2 <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($property->address_line_2) ? $property->address_line_2 : '' }}" name="address_line_2" id="address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2" />
                    <div class="acc__input-error error-address_line_2 text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="city">Town/City <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($property->city) ? $property->city : '' }}" name="city" id="city" class="w-full city" type="text" placeholder="Town/City" />
                    <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                </div>
                <div  class="mt-3">
                    <x-base.form-label for="state">Region/County</x-base.form-label>
                    <x-base.form-input value="{{ isset($property->state) ? $property->state : '' }}" name="state" id="state" class="w-full state" type="text" placeholder="Region/County" />
                </div>
                <div class="mt-3">
                    <x-base.form-label for="postal_code">Post Code <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($property->postal_code) ? $property->postal_code : '' }}" name="postal_code" id="postal_code" class="w-full postal_code" type="text" placeholder="Post Code" />
                    <div class="acc__input-error error-postal_code text-danger text-xs mt-1"></div>
                </div>
                <x-base.form-input value="{{ isset($property->country) ? $property->country : '' }}" name="country" id="country" class="w-full country" type="hidden" />
                        <x-base.form-input value="{{ isset($property->latitude) ? $property->latitude : '' }}" name="latitude" id="latitude" class="w-full latitude" type="hidden" />
                        <x-base.form-input value="{{ isset($property->longitude) ? $property->longitude : '' }}" name="longitude" id="longitude" class="w-full longitude" type="hidden" />
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="adrUpdateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $property->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Company Address Modal Content -->


<!-- BEGIN: Add Occupant Modal Content -->
<x-base.dialog id="addOccupantModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="addOccupantForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center">Add Occupant</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="modal-body">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-12">
                        <x-base.form-label for="occupant_name">Occupant Name <span class="text-danger ml-2">*</span></x-base.form-label>
                        <x-base.form-input value="" name="occupant_name" id="occupant_name" class="w-full" type="text" placeholder="" />
                        <div class="acc__input-error error-occupant_name text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label for="occupant_phone">Occupant Phone</x-base.form-label>
                        <x-base.form-input value="" name="occupant_phone" id="occupant_phone" class="w-full" type="text" placeholder="" />
                        <div class="acc__input-error error-occupant_phone text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label for="occupant_email">Occupant Email</x-base.form-label>
                        <x-base.form-input value="" name="occupant_email" id="occupant_email" class="w-full" type="text" placeholder="" />
                        <div class="acc__input-error error-occupant_email text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label for="due_date">Due Date</x-base.form-label>
                        <x-base.litepicker name="due_date" id="due_date" class="w-full" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                        <div class="acc__input-error error-due_date text-danger text-xs mt-1"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="addOccupantBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="customer_property_id" value="{{ $property->id }}"/>
                <input type="hidden" name="customer_id" value="{{ $property->customer_id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Add Occupant Modal Content -->

<!-- BEGIN: Edit Occupant Modal Content -->
<x-base.dialog id="editOccupantModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="editOccupantForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium inline-flex items-center">Add Occupant</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="modal-body">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-12">
                        <x-base.form-label for="edit_occupant_name">Occupant Name <span class="text-danger ml-2">*</span></x-base.form-label>
                        <x-base.form-input value="" name="occupant_name" id="edit_occupant_name" class="w-full" type="text" placeholder="" />
                        <div class="acc__input-error error-occupant_name text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label for="edit_occupant_phone">Occupant Phone</x-base.form-label>
                        <x-base.form-input value="" name="occupant_phone" id="edit_occupant_phone" class="w-full" type="text" placeholder="" />
                        <div class="acc__input-error error-occupant_phone text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label for="edit_occupant_email">Occupant Email</x-base.form-label>
                        <x-base.form-input value="" name="occupant_email" id="edit_occupant_email" class="w-full" type="text" placeholder="" />
                        <div class="acc__input-error error-occupant_email text-danger text-xs mt-1"></div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label for="edit_due_date">Due Date</x-base.form-label>
                        <x-base.litepicker name="due_date" id="edit_due_date" class="w-full" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                        <div class="acc__input-error error-due_date text-danger text-xs mt-1"></div>
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="editOccupantBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="customer_property_id" value="{{ $property->id }}"/>
                <input type="hidden" name="customer_id" value="{{ $property->customer_id }}"/>
                <input type="hidden" name="id" value="0"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Edit Occupant Modal Content -->