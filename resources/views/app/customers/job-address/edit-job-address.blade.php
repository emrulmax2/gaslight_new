@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center justify-between">
        <h2 class="mr-auto text-lg font-medium">Job Address</h2>
        <div class="flex">
            <x-base.button as="a" href="{{ route('customer.job-addresses', $customer->id) }}" class="shadow-md" variant="primary" >
                <x-base.lucide class="mr-2 h-4 w-4" icon="arrow-left-circle" />
                 Job Address List
            </x-base.button>
        </div>
    </div>
    
    <!-- BEGIN: HTML Table Data -->
    <div class="grid grid-cols-12 lg:gap-6">
        <!-- Customer Information Section -->
        <div class="col-span-12 lg:col-span-3 order-1 lg:order-2">
            <div class="mt-5 box">
                <div class="">
                    <div>
                        <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                            <h2 class="text-base font-medium">Customer Information</h2>
                        </div>
                       <div class="p-5">
                        <div class="mb-3">
                            Name: <span class="font-medium">{{ $customer->full_name }}</span>
                        </div>
                        <div class="mb-3">
                            Email: <span class="font-medium">{{ $customer_contact_info->email }}</span>
                        </div>
                        <div class="mb-3">
                            Phone: <span class="font-medium">{{ $customer_contact_info->mobile }}</span>
                        </div>
                        <div class="mb-3">
                            Address: <span class="font-medium">{{ $customer->full_address }}</span>
                        </div>
                       </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 box lg:block hidden">
                <div class="p-5 flex flex-col justify-center gap-3">
                    <x-base.button class="w-full text-white shadow-md updateJobAddrBtn" type="submit" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Update Address
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button as="a" href="{{ route('company.dashboard') }}" class="mr-1 w-full" data-tw-dismiss="modal" type="button" variant="danger">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="home" />Home
                    </x-base.button>
                </div>
            </div>
        </div>
    
        <!-- Job Address Form Section -->
        <div class="col-span-12 lg:col-span-9 order-2 lg:order-1 mt-5">
            <form method="post" action="#" id="updatejobAddressForm">
                <x-base.dialog.description class="grid grid-cols-12 gap-x-6 gap-y-6 py-0 px-0">
                    <x-base.form-input name="customer_id" id="customer_id" class="w-full" type="hidden" value="{{ $property->customer_id }}" />
                    <x-base.form-input name="property_id" id="property_id" class="w-full" type="hidden" value="{{ $property->id }}" />
                    <div class="col-span-12 sm:col-span-6 box">
                        <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                            <h2 class="text-base font-medium">Job Address Details</h2>
                        </div>
                        <div class="theAddressWrap p-5" id="jobAddressWrap">
                            <div class="mb-3">
                                <x-base.button class="w-full coptyCustomerAddress text-primary" data-tw-dismiss="modal" type="button" variant="secondary">
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
                                <x-base.form-input name="address_line_1" id="customer_address_line_1" class="w-full address_line_1" type="text" value="{{ $property->address_line_1 }}" placeholder="Address Line 1" />
                                <div class="acc__input-error error-address_line_1 text-danger text-xs mt-1"></div>
                            </div>
                            <div class="mb-3">
                                <x-base.form-label for="address_line_2">Address Line 2</x-base.form-label>
                                <x-base.form-input name="address_line_2" id="address_line_2" class="w-full address_line_2" type="text" value="{{ $property->address_line_2 }}" placeholder="Address Line 2 (Optional)" />
                            </div>
                            <div class="mb-3">
                                <x-base.form-label for="city">Town/City</x-base.form-label>
                                <x-base.form-input name="city" id="city" class="w-full city" type="text" value="{{ $property->city }}" placeholder="Town/City" />
                                <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                            </div>
                            <div class="mb-3">
                                <x-base.form-label for="state">Region/County</x-base.form-label>
                                <x-base.form-input name="state" id="state" class="w-full state" type="text" value="{{ $property->state }}" placeholder="Region/County" />
                            </div>
                            <div class="mb-3">
                                <x-base.form-label for="postal_code">Post Code</x-base.form-label>
                                <x-base.form-input name="postal_code" id="postal_code" class="w-full postal_code" type="text" value="{{ $property->postal_code }}" placeholder="Post Code" />
                                <div class="acc__input-error error-postal_code text-danger text-xs mt-1"></div>
                            </div>
                            <x-base.form-input name="country" id="country" class="w-full country" type="hidden" value="{{ $property->country }}" />
                            <x-base.form-input name="latitude" class="w-full latitude" type="hidden" value="{{ $property->latitude }}" />
                            <x-base.form-input name="longitude" class="w-full longitude" type="hidden" value="{{ $property->longitude }}" />
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 box">
                        <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                            <h2 class="text-base font-medium">Occupant's Details</h2>
                        </div>
                       <div class="p-5">
                        <div class="mb-3">
                            <x-base.form-label for="occupant_name">Name</x-base.form-label>
                            <x-base.form-input name="occupant_name" id="occupant_name" class="w-full" type="text" value="{{ $property->occupant_name }}" placeholder="Name" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="occupant_phone">Phone</x-base.form-label>
                            <x-base.form-input name="occupant_phone" id="occupant_phone" class="w-full" type="text" value="{{ $property->occupant_phone }}" placeholder="Phone" />
                        </div>
                        <div class="mb-3">
                            <x-base.form-label for="occupant_email">Email</x-base.form-label>
                            <x-base.form-input name="occupant_email" id="occupant_email" class="w-full" type="email" value="{{ $property->occupant_email }}" placeholder="Email Address" />
                        </div>
                        <h2 class="text-base font-medium mb-2 pt-5">Gas Service Due Date</h2>
                        <div class="mb-3">
                            <x-base.litepicker name="due_date" id="due_date" class="block w-full" value="{{ !empty($property->due_date) ? date('d-m-Y', strtotime($property->due_date)) : '' }}" data-single-mode="true" data-format="DD-MM-YYYY" />
                        </div>
                       </div>
                    </div>
                    <div class="col-span-12">
                        <div class="intro-y box mt-6">
                            <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                                <h2 class="mr-auto text-base font-medium">Note</h2>
                            </div>
                            <div class="p-5">
                                <x-base.form-textarea name="note" id="note" class="w-full h-[80px]" placeholder="Note">{{ $property->note }}</x-base.form-textarea>
                            </div>
                        </div>
                    </div>
                </x-base.dialog.description>
            </form>
        </div>
        <div class="col-span-12 lg:hidden order-3 mt-5">
            <div class="box">
                <div class="p-5 flex flex-col justify-center gap-3">
                    <x-base.button class="w-full text-white shadow-md updateJobAddrBtn" type="submit" variant="success">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                        Update Address
                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                    </x-base.button>
                    <x-base.button as="a" href="{{ route('company.dashboard') }}" class="mr-1 w-full" data-tw-dismiss="modal" type="button" variant="danger">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="home" />Home
                    </x-base.button>
                </div>
            </div>
        </div>
    </div>
       

    <!-- END: HTML Table Data -->

    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/customers/job-address/job-address-create.js')
@endPushOnce