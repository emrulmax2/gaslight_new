<!-- BEGIN: Update Modal Content -->
<x-base.dialog id="jobDtlDescModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="jobDtlDescForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Note</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <x-base.form-label><span class="fieldTitle">Value</span><span class="requiredLabel text-danger hidden ml-1">*</span></x-base.form-label>
                <x-base.form-textarea name="fieldValue" id="fieldValue" class="w-full h-[120px] rounded-[3px]" placeholder=""></x-base.form-textarea>
                <div class="acc__input-error error-fieldValue text-danger text-xs mt-1"></div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateTextBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $job->id }}"/>
                <input type="hidden" name="fieldName" value=""/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Modal Content -->
 
<!-- BEGIN: Update Modal Content -->
<x-base.dialog id="updateJobDataModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updateJobDataForm">
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
                <input type="hidden" name="id" value="{{ $job->id }}"/>
                <input type="hidden" name="fieldName" value=""/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Modal Content -->

<!-- BEGIN: Priority Modal Content -->
<x-base.dialog id="updatePriorityModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updatePriorityForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Job Priority</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="px-5 py-2 bg-slate-100">
                <div class="bg-white">
                    @foreach($priorities as $priority)
                    <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                        <x-base.form-check.label class="font-medium ml-0 block w-full" for="fieldValue_{{ $priority->id }}">{{ $priority->name }}</x-base.form-check.label>
                        <x-base.form-check.input checked="{{ $job->customer_job_priority_id == $priority->id ? 1 : 0 }}" id="fieldValue_{{ $priority->id }}" name="fieldValue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="{{ $priority->id }}"/>
                    </x-base.form-check>
                    @endforeach
                </div>
                <div class="acc__input-error error-fieldValue text-danger text-xs"></div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="savePriorityBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $job->id }}"/>
                <input type="hidden" name="fieldName" value="customer_job_priority_id"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Priority Modal Content -->

<!-- BEGIN: Status Modal Content -->
<x-base.dialog id="updateStatusModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updateStatusForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Job Status</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="px-5 py-2 bg-slate-100">
                <div class="bg-white">
                    @foreach($statuses as $status)
                    <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                        <x-base.form-check.label class="font-medium ml-0 block w-full" for="fieldValue_status_{{ $status->id }}">{{ $status->name }}</x-base.form-check.label>
                        <x-base.form-check.input checked="{{ $job->customer_job_status_id == $status->id ? 1 : 0 }}" id="fieldValue_status_{{ $status->id }}" name="fieldValue" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="{{ $status->id }}"/>
                    </x-base.form-check>
                    @endforeach
                </div>
                <div class="acc__input-error error-fieldValue text-danger text-xs"></div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="saveStatusBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $job->id }}"/>
                <input type="hidden" name="fieldName" value="customer_job_status_id"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Status Modal Content -->
 
<!-- BEGIN: Update Modal Content -->
<x-base.dialog id="updateApointDateModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="updateApointDateForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Update Appointment Details</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="">
                <div>
                    <x-base.form-label for="job_calender_date">Appointment Date</x-base.form-label>
                    <x-base.form-input value="{{ (!empty($job->calendar->date) ? date('d-m-Y', strtotime($job->calendar->date)) : '') }}" name="job_calender_date" id="job_calender_date" class="mx-auto block w-full" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                </div>
                <div class="mt-3 calenderSlot {{ (!empty($job->calendar->calendar_time_slot_id) ? '' : 'hidden') }}">
                    <x-base.form-label for="calendar_time_slot_id">Slot</x-base.form-label>
                    <x-base.tom-select class="w-full" id="calendar_time_slot_id" name="calendar_time_slot_id" data-placeholder="Please Select">
                        <option value="">Please Select</option>
                        @if($slots->count() > 0)
                            @foreach($slots as $slot)
                                <option {{ (isset($job->calendar->calendar_time_slot_id) && $job->calendar->calendar_time_slot_id == $slot->id ? 'Selected' : '') }} value="{{ $slot->id }}">{{ $slot->title }} {{ $slot->start }} {{ $slot->end }}</option>
                            @endforeach
                        @endif
                    </x-base.tom-select>
                    <div class="acc__input-error error-calendar_time_slot_id text-danger text-xs mt-1"></div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateAptBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="{{ $job->id }}"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Update Modal Content -->

<!-- BEGIN: Modal Content -->
<x-base.dialog id="addJobAddressModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="addJobAddressForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Create Job Address</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <x-base.form-input name="customer_id" class="w-full" type="hidden" value="0" />
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