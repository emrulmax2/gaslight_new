<!-- BEGIN: Update Modal Content -->
<x-base.dialog id="customerNoteModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="customerNoteForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Note</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <x-base.form-textarea name="fieldValue" id="fieldValue" class="w-full h-[120px]" placeholder="Note">{{ isset($customer->note) ? $customer->note : '' }}</x-base.form-textarea>
                <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateNoteBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $customer->id }}"/>
                <input type="hidden" name="fieldName" value="note"/>
                <input type="hidden" name="theModel" value="customer"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Modal Content -->
 
<!-- BEGIN: Update Modal Content -->
<x-base.dialog id="updateCustomerDataModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updateCustomerDataForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Data</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div class="grid grid-cols-12 gap-x-3 gap-y-2">
                    <div class="col-span-12">
                        <x-base.form-label><span class="fieldTitle">Value</span><span class="requiredLabel text-danger hidden ml-1">*</span></x-base.form-label>
                        <x-base.form-input value="" name="fieldValue" class="w-full h-[35px] rounded-[3px]" />
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
                <input type="hidden" name="id" value="{{ $customer->id }}"/>
                <input type="hidden" name="fieldName" value=""/>
                <input type="hidden" name="theModel" value="customer"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Modal Content -->

<!-- BEGIN: Company Address Modal Content -->
<x-base.dialog id="customerAddressModal" staticBackdrop>
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="customerAddressForm">
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
                    <x-base.form-input value="{{ isset($customer->address_line_1) ? $customer->address_line_1 : '' }}" name="address_line_1" id="address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
                    <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="address_line_2">Address Line 2 <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($customer->address_line_2) ? $customer->address_line_2 : '' }}" name="address_line_2" id="address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2" />
                    <div class="acc__input-error error-address_line_2 text-danger text-xs mt-1"></div>
                </div>
                <div class="mt-3">
                    <x-base.form-label for="city">Town/City <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($customer->city) ? $customer->city : '' }}" name="city" id="city" class="w-full city" type="text" placeholder="Town/City" />
                    <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                </div>
                <div  class="mt-3">
                    <x-base.form-label for="state">Region/County</x-base.form-label>
                    <x-base.form-input value="{{ isset($customer->state) ? $customer->state : '' }}" name="state" id="state" class="w-full state" type="text" placeholder="Region/County" />
                </div>
                <div class="mt-3">
                    <x-base.form-label for="postal_code">Post Code <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($customer->postal_code) ? $customer->postal_code : '' }}" name="postal_code" id="postal_code" class="w-full postal_code" type="text" placeholder="Post Code" />
                    <div class="acc__input-error error-postal_code text-danger text-xs mt-1"></div>
                </div>
                <x-base.form-input value="{{ isset($customer->country) ? $customer->country : '' }}" name="country" id="country" class="w-full country" type="hidden" />
                        <x-base.form-input value="{{ isset($customer->latitude) ? $customer->latitude : '' }}" name="latitude" id="latitude" class="w-full latitude" type="hidden" />
                        <x-base.form-input value="{{ isset($customer->longitude) ? $customer->longitude : '' }}" name="longitude" id="longitude" class="w-full longitude" type="hidden" />
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="adrUpdateBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="customer_id" value="{{ $customer->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Company Address Modal Content -->

<!-- BEGIN: Reminder Modal Content -->
<x-base.dialog id="reminderModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="reminderForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Automatic Reminder?</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="px-5 py-2 bg-slate-100">
                <div class="bg-white">
                    <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                        <x-base.form-check.label class="font-medium ml-0 block w-full" for="fieldValue_yes">Yes</x-base.form-check.label>
                        <x-base.form-check.input checked="{{ isset($customer->auto_reminder) && $customer->auto_reminder == 1 ? 1 : 0 }}" id="fieldValue_yes" name="fieldValue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="1"/>
                    </x-base.form-check>
                    <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                        <x-base.form-check.label class="font-medium ml-0 block w-full" for="fieldValue_no">No</x-base.form-check.label>
                        <x-base.form-check.input checked="{{ isset($customer->auto_reminder) && $customer->auto_reminder != 1 ? 1 : 0 }}" id="fieldValue_no" name="fieldValue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="0"/>
                    </x-base.form-check>
                </div>
                <div class="acc__input-error error-fieldValue text-danger text-xs"></div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveReminderBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $customer->id }}"/>
                <input type="hidden" name="fieldName" value="auto_reminder"/>
                <input type="hidden" name="theModel" value="customer"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Reminder Modal Content -->

<!-- BEGIN: Customer Name Modal Content -->
<x-base.dialog id="customerNameModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="customerNameForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Customer Name</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description>
               <div>
                    <x-base.form-label for="title_id">Title</x-base.form-label>
                    <x-base.tom-select class="w-full" id="title_id" name="title_id" data-placeholder="Please Select">
                        <option value="">Please Select</option>
                        @if($titles->count() > 0)
                            @foreach($titles as $title)
                                <option {{ $customer->title_id == $title->id ? 'Selected' : '' }} value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        @endif
                    </x-base.tom-select>
               </div>
               <div class="mt-3">
                    <x-base.form-label for="full_name">Full Name <span class="text-danger">*</span></x-base.form-label>
                    <x-base.form-input value="{{ isset($customer->full_name) ? $customer->full_name : '' }}" name="full_name" id="full_name" class="w-full cap-fullname" type="text" placeholder="Full Name" />
                    <div class="acc__input-error error-full_name text-danger text-xs mt-1"></div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveNameBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $customer->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Reminder Modal Content -->