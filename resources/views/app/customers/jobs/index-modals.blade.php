<!-- BEGIN: Modal Content -->
<x-base.dialog id="addJobAddressModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="addJobAddressForm">
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
 
<!-- BEGIN: Modal Content -->
<x-base.dialog id="addCustomerJobModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="addCustomerJobForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Create Job</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description>
                <x-base.form-input name="customer_id" class="w-full" type="hidden" value="{{ $customer->id }}" />
                <div class="mb-3">
                    <x-base.form-label for="customer_property_id">Job Address <span class="text-danger">*</span></x-base.form-label>
                    <div class="relative searchWrap">
                        <x-base.form-input autocomplete="off" name="search_input" class="w-full search_input address_name" type="text" placeholder="Search Customer..." />
                        <x-base.form-input name="customer_property_id" id="customer_property_id" class="w-full the_id_input" type="hidden" value="0" />
                        <div class="searchResultCotainter absolute left-0 top-full shadow bg-white border rounded-md w-full z-50" style="display: none;">
                            <div class="resultWrap">
                                <div class="p-10 flex justify-center items-center"><span class="h-10 w-10"><svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="#2d3748"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="4"><circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg></span></div>
                            </div>

                            <x-base.button type="button" class="addAddressBtn text-white font-bold w-full rounded-md rounded-tl-none rounded-tr-none" variant="success">
                                <x-base.lucide class="mr-2 h-4 w-4 stroke-[1.3]" icon="plus-circle" />
                                Add Address
                            </x-base.button>
                        </div>
                    </div>
                    <div class="acc__input-error error-customer_property_id text-danger text-xs mt-1"></div>
                </div>
                <div class="mb-3">
                    <x-base.form-label for="description">Job description</x-base.form-label>
                    <x-base.form-input name="description" id="description" class="w-full" type="text" placeholder="Short Description" />
                </div>
                <div class="mb-3">
                    <x-base.form-label for="details">Job Details</x-base.form-label>
                    <x-base.form-textarea name="details" id="details" class="w-full h-[80px]" placeholder="Details..."></x-base.form-textarea>
                </div>
                <div class="grid grid-cols-12 gap-x-6 gap-y-3">
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label for="estimated_amount">Estimated Job Value</x-base.form-label>
                        <x-base.form-input step="any" name="estimated_amount" id="estimated_amount" class="w-full" type="number" placeholder="0.00" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label for="customer_job_priority_id">Priority</x-base.form-label>
                        <x-base.tom-select class="w-full" id="customer_job_priority_id" name="customer_job_priority_id" data-placeholder="Please Select">
                            @if($priorities->count() > 0)
                                @foreach($priorities as $priority)
                                    <option value="">Please Select</option>
                                    <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                @endforeach
                            @endif
                        </x-base.tom-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label for="due_date">Due Date</x-base.form-label>
                        <x-base.litepicker name="due_date" id="due_date" class="mx-auto block w-full" data-single-mode="true" data-format="DD-MM-YYYY" />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label for="customer_job_status_id">Job Status</x-base.form-label>
                        <x-base.tom-select class="w-full" id="customer_job_status_id" name="customer_job_status_id" data-placeholder="Please Select">
                            @if($statuses->count() > 0)
                                @foreach($statuses as $status)
                                    <option value="">Please Select</option>
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            @endif
                        </x-base.tom-select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <x-base.form-label for="reference_no">Job Ref No</x-base.form-label>
                        <x-base.form-input name="reference_no" id="reference_no" class="w-full" type="text" placeholder="Reference No" />
                    </div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="jobSaveBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save Job
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->