@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center">
        <h2 class="mr-auto text-lg font-medium">New Customer</h2>
        <div class=" sm:w-auto flex gap-2">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>
    <form method="post" action="#" id="customerCreateForm">
        <div class="mt-5 grid grid-cols-12 gap-6">
            <div class="intro-y col-span-12 lg:col-span-9">
                <!-- Personal Information Section -->
                <div class="intro-y box">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Personal Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-1">
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="title_id">Title</x-base.form-label>
                                    <x-base.tom-select class="w-full" id="title_id" name="title_id" data-placeholder="Please Select">
                                        <option value="">Please Select</option>
                                        @if($titles->count() > 0)
                                            @foreach($titles as $title)
                                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                                            @endforeach
                                        @endif
                                    </x-base.tom-select>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="full_name">Full Name</x-base.form-label>
                                    <x-base.form-input name="full_name" 
                                    id="full_name" 
                                    class="w-full" 
                                    type="text" 
                                    placeholder="Full Name" 
                                    oninput="this.value = this.value.toUpperCase()"
                                    />
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="company_name">Company Name</x-base.form-label>
                                    <x-base.form-input name="company_name" id="company_name" class="w-full" type="text" placeholder="Company Name" />
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="vat_no">VAT No</x-base.form-label>
                                    <x-base.form-input name="vat_no" id="vat_no" class="w-full" type="text" placeholder="VAT No" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Address Section -->
                <div class="intro-y box mt-6">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Address</h2>
                    </div>
                    <div class="p-5 theAddressWrap" id="customerAddressWrap">
                        <div class="grid grid-cols-12 gap-1">
                            <div class="col-span-12 lg:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="customer_address_lookup">Address Lookup</x-base.form-label>
                                    <x-base.form-input name="address_lookup" id="customer_address_lookup" class="w-full theAddressLookup" type="text" placeholder="Search address here..." />
                                </div>
                            </div>
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="customer_address_line_1">Address Line 1</x-base.form-label>
                                <x-base.form-input name="address_line_1" id="customer_address_line_1" class="w-full address_line_1" type="text" placeholder="Address Line 1" />
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="address_line_2">Address Line 2</x-base.form-label>
                                <x-base.form-input name="address_line_2" id="address_line_2" class="w-full address_line_2" type="text" placeholder="Address Line 2 (Optional)" />
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="city">Town/City</x-base.form-label>
                                <x-base.form-input name="city" id="city" class="w-full city" type="text" placeholder="Town/City" />
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="state">Region/County</x-base.form-label>
                                <x-base.form-input name="state" id="state" class="w-full state" type="text" placeholder="Region/County" />
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <div class="m-1">
                                <x-base.form-label for="postal_code">Post Code</x-base.form-label>
                                <x-base.form-input name="postal_code" id="postal_code" class="w-full postal_code" type="text" placeholder="Post Code" />
                            </div>
                        </div>
                    </div>
                        <x-base.form-input name="country" id="country" class="w-full country" type="hidden" value="" />
                        <x-base.form-input name="latitude" id="latitude" class="w-full latitude" type="hidden" value="" />
                        <x-base.form-input name="longitude" id="longitude" class="w-full longitude" type="hidden" value="" />
                    </div>
                </div>
    
                <!-- Contact Information Section -->
                <div class="intro-y box mt-6">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Contact Information</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-1">
                            <div class="col-span-12 sm:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="mobile">Mobile</x-base.form-label>
                                    <x-base.form-input name="mobile" id="mobile" class="w-full" type="text" placeholder="Mobile" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="phone">Phone</x-base.form-label>
                                    <x-base.form-input name="phone" id="phone" class="w-full" type="text" placeholder="Phone" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="email">Email</x-base.form-label>
                                    <x-base.form-input name="email" id="email" class="w-full" type="email" placeholder="Email Address" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="m-1">
                                    <x-base.form-label for="other_email">Other Email</x-base.form-label>
                                    <x-base.form-input name="other_email" id="other_email" class="w-full" type="email" placeholder="Secondary Email Address" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Note Section -->
            <div class="intro-y box mt-6">
                    <div class="flex flex-col items-center border-b border-slate-200/60 p-5 dark:border-darkmode-400 sm:flex-row">
                        <h2 class="mr-auto text-base font-medium">Note</h2>
                    </div>
                    <div class="p-5">
                        <x-base.form-textarea name="note" id="note" class="w-full h-[120px]" placeholder="Note"></x-base.form-textarea>
                    </div>
                </div>
            </div>
    
            <div class="intro-y col-span-12 lg:col-span-3">
                <!-- Automatic Reminder Section -->
                <div class="intro-y box">
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12">
                                <x-base.form-switch class="w-full mt-3 sm:ml-auto sm:mt-0 sm:w-auto">
                                    <x-base.form-switch.label class="ml-0 sm:ml-2" for="auto_reminder">Automatic Reminder?</x-base.form-switch.label>
                                    <x-base.form-switch.input checked class="ml-3 mr-0" id="auto_reminder" name="auto_reminder" value="1" type="checkbox" />
                                </x-base.form-switch>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Save and Cancel Buttons -->
                <div class="intro-y box mt-6">
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12">
                                <div class="flex flex-col space-y-4">
                                    <x-base.button type="submit" id="customerSaveBtn" class="w-full text-white shadow-md" variant="success">
                                        <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                                        Save Customer
                                        <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                    </x-base.button>
                                    <x-base.button as="a" href="{{ (isset(request()->record) && !empty(request()->record) ? route('jobs.create', ['record' => request()->record]) :  route('customers')) }}" class="w-full" variant="danger">
                                        <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                                        Cancel
                                    </x-base.button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('app.action-modals')
@endsection

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/customers/customers-create.js')
@endPushOnce