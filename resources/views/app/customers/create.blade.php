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
        <div class="intro-y box mt-5 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Personal Info
                </h2>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Full Name</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="full_name" name="full_name" value="" placeholder="Mr. John Doe" class="cap-fullname w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="user" />
                        </div>
                        <div class="acc__input-error error-full_name text-danger text-xs"></div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Company Name</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="company_name" name="company_name" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="building" />
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">VAT Number</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="vat_no" name="vat_no" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="hash" />
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2 theAddressWrap" id="customerAddressWrap">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Address
                </h2>
            </div>
            <div class="px-2 py-3 bg-white">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Address Lookup</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="customer_address_lookup" name="address_lookup" value="" placeholder="Search address here..." class="theAddressLookup cap-fullname w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 mt-2 gap-2">
                <div class="col-span-12 sm:col-span-6">
                    <div class="px-2 py-3 bg-white">
                        <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                            <div class="w-full">
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Address Line 1</div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="address_line_1" name="address_line_1" value="" placeholder="" class="address_line_1 w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <div class="px-2 py-3 bg-white">
                        <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                            <div class="w-full">
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Address Line 2</div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="address_line_2" name="address_line_2" value="" placeholder="" class="address_line_2 w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <div class="px-2 py-3 bg-white">
                        <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                            <div class="w-full">
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Town/City</div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="city" name="city" value="" placeholder="" class="city w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <div class="px-2 py-3 bg-white">
                        <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                            <div class="w-full">
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Region/County</div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="state" name="state" value="" placeholder="" class="state w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <div class="px-2 py-3 bg-white">
                        <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                            <div class="w-full">
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Post Code</div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="postal_code" name="postal_code" value="" placeholder="" class="postal_code w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-base.form-input name="country" id="country" class="w-full country" type="hidden" value="" />
            <x-base.form-input name="latitude" id="latitude" class="w-full latitude" type="hidden" value="" />
            <x-base.form-input name="longitude" id="longitude" class="w-full longitude" type="hidden" value="" />
        </div>

        
        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Contact Info
                </h2>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Mobile</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="mobile" name="mobile" value="" placeholder="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="smartphone" />
                        </div>
                        <div class="acc__input-error error-mobile text-danger text-xs"></div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Phone</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="phone" name="phone" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="phone" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Email Address</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="email" name="email" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="email"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="mail" />
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Other Email Address</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="other_email" name="other_email" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="email"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="mails" />
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Customer Note
                </h2>
            </div>
            <div class="px-2 py-3 bg-white">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Note</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="note" name="note" value="" placeholder="Write a note..." class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="notebook-pen" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Automatic Reminder
                </h2>
            </div>
            <div class="px-2 py-1 bg-white">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="theDesc w-full relative">
                            <div class="bg-white">
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="auto_reminder_yes">Yes</x-base.form-check.label>
                                    <x-base.form-check.input checked="1" id="auto_reminder_yes" name="auto_reminder" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="1"/>
                                </x-base.form-check>
                                <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative">
                                    <x-base.form-check.label class="font-medium ml-0 block w-full" for="auto_reminder_no">No</x-base.form-check.label>
                                    <x-base.form-check.input id="auto_reminder_no" name="auto_reminder" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="0"/>
                                </x-base.form-check>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="intro-y box mt-2 rounded-none border-none px-2 py-2">
            <div class="flex justify-center items-center">
                <x-base.button type="submit" id="customerSaveBtn" class="w-auto mr-2 text-white shadow-md" variant="success">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save Customer
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <x-base.button as="a" href="{{ (isset(request()->record) && !empty(request()->record) ? route('jobs.create', ['record' => request()->record]) :  route('customers')) }}" class="w-auto" variant="danger">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />
                    Cancel
                </x-base.button>
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