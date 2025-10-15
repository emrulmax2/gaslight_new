@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center justify-between">
        <h2 class="mr-auto text-lg font-medium">Job Address</h2>
        <div class="gap-2">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>
    
    <form method="post" action="#" id="addJobAddressForm">
        <div class="settingsBox mt-5">
            <h3 class="font-medium leading-none mb-3 text-dark">Customer Information</h3>
            <div class="box rounded-md p-0 overflow-hidden">
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="user" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">
                            {{ isset($customer->customer_full_name) && !empty($customer->customer_full_name) ? $customer->customer_full_name : 'N/A' }}
                        </span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="map-pin" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{!! $customer->full_address_with_html !!}</span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="border-b flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="mail" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ $customer_contact_info->email }}</span>
                    </div>
                </a>
                <a href="javascript:void(0);" class="flex w-full items-start px-5 py-3">
                    <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="smartphone" />
                    <div>
                        <span class="font-normal text-slate-400 text-xs block">{{ $customer_contact_info->mobile }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2 theAddressWrap" id="jobAddressWrap">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Address
                </h2>
                <x-base.button class="coptyCustomerAddress ml-auto text-white" size="sm" type="button" variant="primary" >
                    <x-base.lucide class="mr-2 h-4 w-4" icon="copy" /> Copy Customer Address 
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#164e63e6" icon="oval" />
                </x-base.button>
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
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Address Line 1<span class="text-danger ml-1">*</span></div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="address_line_1" name="address_line_1" value="" placeholder="" class="address_line_1 w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                                <div class="acc__input-error error-address_line_1 text-danger text-xs"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <div class="px-2 py-3 bg-white">
                        <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                            <div class="w-full">
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Address Line 2<span class="text-danger ml-1">*</span></div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="address_line_2" name="address_line_2" value="" placeholder="" class="address_line_2 w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                                <div class="acc__input-error error-address_line_2 text-danger text-xs"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                    <div class="px-2 py-3 bg-white">
                        <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                            <div class="w-full">
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Town/City<span class="text-danger ml-1">*</span></div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="city" name="city" value="" placeholder="" class="city w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                                <div class="acc__input-error error-city text-danger text-xs"></div>
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
                                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Post Code<span class="text-danger ml-1">*</span></div>
                                <div class="theDesc w-full relative">
                                    <x-base.form-input id="postal_code" name="postal_code" value="" placeholder="" class="postal_code w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="map-pin" />
                                </div>
                                <div class="acc__input-error error-postal_code text-danger text-xs"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-base.form-input name="country" id="country" class="w-full country" type="hidden" value="" />
            <x-base.form-input name="latitude" id="latitude" class="w-full latitude" type="hidden" value="" />
            <x-base.form-input name="longitude" id="longitude" class="w-full longitude" type="hidden" value="" />
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2  occupantSection">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium flex items-center">
                    <x-base.form-switch.input class="mr-3 relative -top-[1px]" id="has_occupants" name="has_occupants" value="1" type="checkbox" />
                    <label data-tw-merge for="has_occupants" class="cursor-pointer font-medium mr-5">Occupant</label>
                </h2>
            </div>
            <div id="occupantWrap" style="display: none;">
                <div class="px-2 py-3 bg-white">
                    <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                        <div class="w-full">
                            <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Name</div>
                            <div class="theDesc w-full relative">
                                <x-base.form-input id="occupant_name" name="occupant_name" value="" placeholder="Mr. John Doe" class="cap-fullname w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="user" />
                            </div>
                            <div class="acc__input-error error-occupant_name text-danger text-xs"></div>
                        </div>
                    </div>
                </div>
                <div class="px-2 py-3 bg-white mt-2">
                    <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                        <div class="w-full">
                            <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Phone</div>
                            <div class="theDesc w-full relative">
                                <x-base.form-input id="occupant_phone" name="occupant_phone" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text"  autocomplete="off"/>
                                <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="smartphone" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-2 py-3 bg-white mt-2">
                    <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                        <div class="w-full">
                            <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Email</div>
                            <div class="theDesc w-full relative">
                                <x-base.form-input id="occupant_email" name="occupant_email" value="" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="email"  autocomplete="off"/>
                                <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="mail" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-2 py-3 bg-white mt-2">
                    <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                        <div class="w-full">
                            <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Gas Service Due Date</div>
                            <div class="theDesc w-full relative">
                                <x-base.litepicker name="due_date" id="due_date" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" data-single-mode="true" data-format="DD-MM-YYYY" autocomplete="off" />
                                <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        <div class="intro-y box mt-2 rounded-none border-none px-2 py-2">
            <div class="flex justify-center items-center">
                <x-base.button class="w-auto mr-2 text-white shadow-md addJobAddrBtn" type="submit" variant="success">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Save Address
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <x-base.button as="a" href="{{ route('customer.job-addresses', $customer->id) }}" class="w-auto" data-tw-dismiss="modal" type="button" variant="danger">
                    <x-base.lucide class="mr-2 h-4 w-auto" icon="x-circle" /> Cancel
                </x-base.button>
            </div>
        </div>
        <input type="hidden" name="customer_id" value="{{ $customer->id }}"/>
    </form>



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