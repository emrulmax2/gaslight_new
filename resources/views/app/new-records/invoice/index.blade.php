@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $form->name }}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <form method="post" action="#" id="certificateForm">
        <input type="hidden" name="job_form_id" value="{{ $form->id }}"/>
        <input type="hidden" name="invoice_id" id="invoice_id" value="0"/>
        <input type="hidden" name="non_vat_invoice" id="non_vat_invoice" value="{{ $non_vat_invoice }}"/>
        <input type="hidden" name="vat_number" id="vat_number" value="{{ $vat_number }}"/>
        <!-- BEGIN: HTML Table Data -->
        <div class="intro-y box mt-5 bg-slate-200 rounded-none border-none px-2 py-2">
            <div class="px-2 py-3 invoiceNoWrap bg-white">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer invoiceNoBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">INVOICE #</div>
                        <div class="theDesc">N/A</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"></span>
                    <input type="hidden" id="invoice_number" name="invoice_number" value="0" class="theId"/>
                </a>
            </div>
        </div>
        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Linked Job
                </h2>
            </div>
            <div class="px-2 py-3 jobWrap bg-white">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer jobBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job</div>
                        <div class="theDesc">Click here to select a job</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
                    <input type="hidden" id="job_id" name="job_id" value="0" class="theId"/>
                </a>
            </div>
        </div>
        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Customer Details
                </h2>
            </div>
            <div class="px-2 py-3 customerWrap bg-white">
                <!--<a href="{{ route('customers') }}" data-key="record_url" data-value="{{ url()->current() }}" class="theStorageTrigger flex justify-between items-center cursor-pointer customerBlock"> -->
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer customerBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Customer</div>
                        <div class="theDesc">Click here to select a customer</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
                    <input type="hidden" id="customer_id" name="customer_id" value="0" class="theId"/>
                </a>
            </div>
            <div class="px-2 py-3 mt-2 customerAddressWrap bg-white" style="display: none;">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer customerAddressBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Customer Address</div>
                        <div class="theDesc">Click here to add customer address</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"></span>
                    <input type="hidden" name="customer_address_id" value="0" class="theId"/>
                </a>
            </div>
            <div class="px-2 py-3 mt-2 customerPropertyWrap bg-white" style="display: none;">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer customerPropertyBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Address</div>
                        <div class="theDesc">Click here to add job address</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
                    <input type="hidden" name="customer_property_id" value="0" class="theId"/>
                </a>
            </div>
            <div class="px-2 py-3 mt-2 customerPropertyOccupantWrap bg-white" style="display: none;">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer customerPropertyOccupantBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Occupant's Details</div>
                        <div class="theDesc">Click here to add job address occupant</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
                    <input type="hidden" name="customer_property_occupant_id" value="0" class="theId"/>
                </a>
            </div>
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Items
                </h2>
            </div>
            <div class="allItemsWrap mb-2" style="display: none;"></div>
            <a href="javascript:void(0);" class="addItemBtn text-primary flex justify-between items-center mb-2 p-3 bg-white text-base">Add Item<x-base.lucide class="ml-auto h-4 w-4" icon="plus-circle" /></a>
            
            <div class="allDiscountItemWrap mb-2" style="display: none;"></div>
            <a href="javascript:void(0);" class="addDiscountBtn text-primary flex justify-between items-center p-3 bg-white text-base">Add Discount<x-base.lucide class="ml-auto h-4 w-4" icon="plus-circle" /></a>
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 py-2">
            <div class="px-2 py-3 advanceWrap bg-white">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer advanceBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Payment To Date</div>
                        <div class="theDesc">{{ Number::currency(0, 'GBP') }}</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="pencil" /></span>
                </a>
            </div>
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 py-2">
            <div class="px-2 py-3 calculationWrap bg-white">
                <div class="lineTotalBlock border-b border-slate-300 mb-3" style="display: none;">
                    <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase subTotalBlock">
                        <div class="text-slate-500 heLabel">Sub Total</div>
                        <div class="ml-auto w-[80px] text-slate-700 text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
                    </div>
                    <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase discountBlock" style="display: none;">
                        <div class="text-slate-500 heLabel">Discount</div>
                        <div class="ml-auto w-[80px] text-danger text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
                    </div>
                    <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase vatTotalBlock" style="display: none;">
                        <div class="text-slate-500 heLabel">Vat Total</div>
                        <div class="ml-auto w-[80px] text-slate-700 text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
                    </div>
                </div>
                <div class="calculationBlock">
                    <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase invoiceTotalBlock">
                        <div class="text-slate-500 heLabel">Total</div>
                        <div class="ml-auto w-[80px] text-slate-700 text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
                    </div>
                    <div class="flex justify-between font-medium text-xs leading-none mb-2 uppercase invoiceAdvanceBlock">
                        <div class="text-slate-500 heLabel">Payment To Date</div>
                        <div class="ml-auto w-[80px] text-slate-700 text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
                    </div>
                    <div class="flex justify-between font-medium  text-xs leading-none uppercase invoiceBalanceBlock">
                        <div class="text-slate-800 heLabel">Balance Due</div>
                        <div class="ml-auto w-[80px] text-[#000000] text-right theDesc">{{ Number::currency(0, 'GBP') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 py-2">
            <div class="px-2 py-3 bg-white">
                <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Invoice Date</div>
                        <div class="theDesc w-full relative">
                            <x-base.litepicker id="issued_date" name="issued_date" value="{{ date('d-m-Y') }}" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 py-2">
            <div class="px-2 py-3 invoiceNoteWrap bg-white">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer invoiceNoteBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Notes for Customer</div>
                        <div class="theDesc">N/A</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="pencil" /></span>
                </a>
            </div>
        </div>

        <div class="intro-y box mt-2 rounded-none border-none px-2 py-2">
            <div class="flex justify-center items-center">
                <x-base.button class="w-full sm:w-auto text-white" id="saveCertificateBtn" type="submit" variant="success">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Create Invoice
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </div>
        </div>
    </form>

    <!-- END: HTML Table Data -->

    @include('app.new-records.invoice.modal')
    @include('app.new-records.modal')
    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/vendors/tom-select.css')
    @vite('resources/css/custom/signature.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/tom-select.js')
    @vite('resources/js/vendors/sign-pad.min.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/new-records/create.js')
    @vite('resources/js/app/new-records/invoice.js')
@endPushOnce