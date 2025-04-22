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
        <input type="hidden" name="certificate_id" id="certificate_id" value="0"/>
        <!-- BEGIN: HTML Table Data -->
        <div class="intro-y box mt-5 bg-slate-200 rounded-none border-none px-2 pb-2">
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
                <a href="{{ route('customers') }}" data-key="record_url" data-value="{{ url()->current() }}" class="theStorageTrigger flex justify-between items-center cursor-pointer customerBlock">
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
                    Appliance & Inspection
                </h2>
            </div>
            <div class="px-2 py-3 uhwSystemWrap bg-white">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer uhwSystemBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Unvented Hot Water System</div>
                        <div class="theDesc">0/13</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
                </a>
            </div>
            <div class="px-2 py-3 mt-2 inspectionRecWrap bg-white">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer inspectionRecBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Inspection Record</div>
                        <div class="theDesc">0/22</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
                </a>
            </div>
        </div>

        @php 
            $inspectionDeate = date('d-m-Y');
            $nextInspectionDate = date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate)));
        @endphp
        <div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
            <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
                <h2 class="mr-auto text-base font-medium">
                    Signature
                </h2>
            </div>
            <div class="px-2 py-3 bg-white">
                <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Todays Date</div>
                        <div class="theDesc w-full relative">
                            <x-base.litepicker id="inspection_date" name="inspection_date" value="{{ $inspectionDeate }}" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer nextDateBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Next Inspection Date</div>
                        <div class="theDesc w-full relative">
                            <x-base.litepicker id="next_inspection_date" name="next_inspection_date" value="{{ $nextInspectionDate }}" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center cursor-pointer receivedByBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Received By</div>
                        <div class="theDesc w-full relative">
                            <x-base.form-input id="received_by" name="received_by" value="" placeholder="Mr. John Doe" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off"/>
                            <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="user" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-3 relationWrap bg-white mt-2">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer relationBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Relation</div>
                        <div class="theDesc">N/A</div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
                    <input type="hidden" id="relation_id" name="relation_id" value="0" class="theId"/>
                </a>
            </div>
            <div class="px-2 py-3 signatureImgWrap bg-white mt-2" style="display: none;">
                <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer signatureImgBlock">
                    <div>
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Existing Signature</div>
                        <div class="theDesc">
                            <img src="" alt="Existing Signature" class="theSignature h-[60px] w-auto inline-block"/>
                        </div>
                    </div>
                    <span style="flex: 0 0 16px; margin-left: 20px;"></span>
                </a>
            </div>

            <div class="px-2 py-3 bg-white mt-2">
                <div class="flex justify-between items-center signatureBlock">
                    <div class="w-full">
                        <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Signature</div>
                        <div class="theDesc w-full relative">
                            <div class="gsfSignature border rounded-none h-auto pt-5 bg-slate-100 rounded-b-none flex justify-center items-center">
                                <x-creagia-signature-pad name='sign'
                                    border-color="#e5e7eb"
                                    submit-name="Save"
                                    clear-name="Clear Signature"
                                    submit-id="signSaveBtn"
                                    clear-id="clear"
                                    pad-classes="w-auto h-48 bg-white mt-0"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="intro-y box mt-2 rounded-none border-none px-2 py-2">
            <div class="flex justify-center items-center">
                <x-base.button class="w-full sm:w-auto text-white" id="saveCertificateBtn" type="button" variant="success">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Create Certificate
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </div>
        </div>
    </form>

    <!-- END: HTML Table Data -->

    @include('app.new-records.unvented_hot_water_cylinders.modal')
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
    @vite('resources/js/app/new-records/unvented-hot-water-cylinders.js')
@endPushOnce