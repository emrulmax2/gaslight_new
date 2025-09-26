<!-- BEGIN: Modal Content -->
<x-base.dialog id="addPricingPackageModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="addPricingPackageForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add Pricing Package</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="title">Title <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="title" id="title" class="w-full" type="text" />
                    <div class="acc__input-error error-title text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="subtitle">SubTitle <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="subtitle" id="subtitle" class="w-full" type="text" />
                    <div class="acc__input-error error-subtitle text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="period">Period <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-select name="period" id="period" class="w-full">
                        <option value="">Please Select</option>
                        <option value="Free Trail">Free Trail</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </x-base.form-select>
                    <div class="acc__input-error error-period text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="price">Price</x-base.form-label>
                    <x-base.form-input name="price" id="price" class="w-full" type="number" step="any" />
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="stripe_plan">Stripe Plan ID</x-base.form-label>
                    <x-base.form-input name="stripe_plan" id="stripe_plan" class="w-full" type="text" />
                    <div class="acc__input-error error-stripe_plan text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-12">
                    <x-base.form-label for="description">Description</x-base.form-label>
                    <x-base.form-textarea rows="3" name="description" id="description" class="w-full"></x-base.form-textarea>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="float-left mt-2">
                    <x-base.form-check>
                        <div data-tw-merge class="flex items-center">
                            <label data-tw-merge for="active" class="cursor-pointer mr-5">VAT Registered</label>
                            <x-base.form-switch.input class="" id="active" name="active" value="1" type="checkbox" />
                        </div>
                    </x-base.form-check>
                </div>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="savePackBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Add Package
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->
 
<!-- BEGIN: Modal Content -->
<x-base.dialog id="editPricingPackageModal" staticBackdrop size="xl">
    <x-base.dialog.panel>
        <form method="post" action="#" id="editPricingPackageForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Update Pricing Package</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-3">
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="edit_title">Title <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="title" id="edit_title" class="w-full" type="text" />
                    <div class="acc__input-error error-title text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="edit_subtitle">SubTitle <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="subtitle" id="edit_subtitle" class="w-full" type="text" />
                    <div class="acc__input-error error-subtitle text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="edit_period">Period <span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-select name="period" id="edit_period" class="w-full">
                        <option value="">Please Select</option>
                        <option value="Free Trail">Free Trail</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </x-base.form-select>
                    <div class="acc__input-error error-period text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="edit_price">Price</x-base.form-label>
                    <x-base.form-input name="price" id="edit_price" class="w-full" type="number" step="any" />
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="edit_stripe_plan">Stripe Plan ID</x-base.form-label>
                    <x-base.form-input name="stripe_plan" id="edit_stripe_plan" class="w-full" type="text" />
                    <div class="acc__input-error error-stripe_plan text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <x-base.form-label for="edit_order">Order<span class="text-danger ml-2">*</span></x-base.form-label>
                    <x-base.form-input name="order" id="edit_order" class="w-full" type="number" step="1" />
                    <div class="acc__input-error error-order text-danger text-xs mt-1"></div>
                </div>
                <div class="col-span-12 sm:col-span-12">
                    <x-base.form-label for="edit_description">Description</x-base.form-label>
                    <x-base.form-textarea rows="3" name="description" id="edit_description" class="w-full"></x-base.form-textarea>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="float-left mt-2">
                    <x-base.form-check>
                        <div data-tw-merge class="flex items-center">
                            <label data-tw-merge for="edit_active" class="cursor-pointer mr-5">VAT Registered</label>
                            <x-base.form-switch.input class="" id="edit_active" name="active" value="1" type="checkbox" />
                        </div>
                    </x-base.form-check>
                </div>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="editPackBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Add Package
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="id" value="0"/>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->