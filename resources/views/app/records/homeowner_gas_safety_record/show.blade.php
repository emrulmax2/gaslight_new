@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $form->name }}</h2>
    </div>
    <form method="post" action="#" id="gasSafetyRecordForm">
        <div class="grid grid-cols-11 gap-x-6 pb-20 mt-5">
            <div class="intro-y col-span-12 max-sm:mt-5 sm:col-span-2 order-2 sm:order-1">
                <div class="sticky top-0">
                    <div class="flex flex-col justify-center items-center shadow-md rounded-md bg-white p-5">
                        <x-base.button as="a" href="{{ route('records', [$form->slug, $gsr->customer_job_id]) }}" class="w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#164e63] [&.active]:text-white hover:bg-[#164e63] focus:bg-[#164e63] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="Pencil" />
                            Edit
                        </x-base.button>
                        <x-base.button value="1" onclick="this.form.submit_type.value = this.value" type="submit" class="formSubmits submit_1 w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                            Approve
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        <x-base.button value="2" onclick="this.form.submit_type.value = this.value" type="submit" class="formSubmits submit_2 w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#0d9488] [&.active]:text-white hover:bg-[#0d9488] focus:bg-[#0d9488] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                            Approve & Email
                            <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                        </x-base.button>
                        @if(!empty($thePdf))
                        <x-base.button as="a" href="{{ $thePdf }}" class="w-full mb-2 border-0 cursor-pointer text-slate-500 shadow-none [&.active]:bg-[#3b5998] [&.active]:text-white hover:bg-[#3b5998] focus:bg-[#3b5998] hover:text-white focus:text-white">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="download" />
                            Download
                        </x-base.button>
                        @endif
                    </div>
                    <input type="hidden" value="1" name="submit_type"/>
                </div>
            </div>
            <div class="intro-y col-span-12 sm:col-span-9 order-1 sm:order-2">
                <div class="intro-y box p-5">
                    @if(!empty($thePdf))
                        <object class="pdfViewer" data="{{ $thePdf }}" type="application/pdf">
                            <embed src="{{ $thePdf }}" type="application/pdf">
                                <p>This browser does not support PDFs. Please download the PDF to view it: <a target="_blank" href="{{ $thePdf }}">Download PDF</a>.</p>
                            </embed>
                        </object>
                    @else
                        <div class="header bg-primary p-5">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <div class="col-span-12 lg:col-span-2">
                                    <img class="w-28" src="{{ Vite::asset('resources/images/gas_safe_register_dark.png') }}" alt="Gas Safe Register Logo">
                                </div>
                                <div class="col-span-12 lg:col-span-8 text-center">
                                    <h1 class="text-white text-2xl leading-none mb-1">Homeowner / Landlord Gas Safety Record</h1>
                                    <div class="text-white text-[12px] leading-[20px]">
                                        This inspection is for gas safety purposes only to comply with the Gas Safety (Installation and Use) Regulations. Flues have been inspected visually and checked for satisfactory evacuation of products of combustion
                                        A detailed internal inspection of the flue integrity, construction and lining has NOT been carried out.
                                        Registered Business/engineer details can be checked at www.gassaferegister.co.uk or by calling 0800 408 5500
                                    </div>
                                </div>
                                <div class="col-span-12 lg:col-span-2">
                                    <div class="text-center">
                                        <x-base.form-label class="text-white uppercase font-medium text-[12px] leading-none mb-2">Certificate Number</x-base.form-label>
                                        <x-base.form-input name="certificate_number" id="certificate_number" value="{{ $gsr->certificate_number }}" class="inline-block w-32 text-center rounded-none h-[35px] font-medium text-primary" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="recordInfo mt-3">
                            <x-base.table bordered>
                                <x-base.table.thead>
                                    <x-base.table.tr>
                                        <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[12px] uppercase px-2 py-1">
                                            COMPANY / INSTALLER
                                        </x-base.table.th>
                                        <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[12px] uppercase px-2 py-1">
                                            INSPECTION / INSTALLATION ADDRESS
                                        </x-base.table.th>
                                        <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 text-white text-[12px] uppercase px-2 py-1">
                                            LANDLORD / AGENT / CUSTOMER
                                        </x-base.table.th>
                                    </x-base.table.tr>
                                </x-base.table.thead>
                                <x-base.table.tbody>
                                    <x-base.table.tr>
                                        <x-base.table.td class="w-[50%] p-0 border-primary align-top">
                                            <x-base.table class="border-none">
                                                <x-base.table.tbody>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="w-[50%] p-0 border-l-0 border-t-0 border-b-0 border-primary align-top">
                                                            <x-base.table class="border-none">
                                                                <x-base.table.tbody>
                                                                    <x-base.table.tr>
                                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 text-[12px] w-[110px] tracking-normal align-top">Engineer</x-base.table.td>
                                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-[60px] pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->user->name) && !empty($gsr->user->name) ? $gsr->user->name : '') }}</x-base.table.td>
                                                                    </x-base.table.tr>
                                                                    <x-base.table.tr>
                                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[110px] tracking-normal align-top">GAS SAFE REG.</x-base.table.td>
                                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->user->company->gas_safe_registration_no) && !empty($gsr->user->company->gas_safe_registration_no) ? $gsr->user->company->gas_safe_registration_no : '') }}</x-base.table.td>
                                                                    </x-base.table.tr>
                                                                    <x-base.table.tr>
                                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[110px] tracking-normal align-top">ID CARD NO.</x-base.table.td>
                                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->user->gas_safe_id_card) && !empty($gsr->user->gas_safe_id_card) ? $gsr->user->gas_safe_id_card : '') }}</x-base.table.td>
                                                                    </x-base.table.tr>
                                                                    <x-base.table.tr>
                                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[110px] tracking-normal align-top">&nbsp;</x-base.table.td>
                                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top"></x-base.table.td>
                                                                    </x-base.table.tr>
                                                                </x-base.table.tbody>
                                                            </x-base.table>
                                                        </x-base.table.td>
                                                        <x-base.table.td class="w-[50%] p-0 border-l-0 border-t-0 border-r-0 border-b-0 align-top">
                                                            <x-base.table class="border-none">
                                                                <x-base.table.tbody>
                                                                    <x-base.table.tr>
                                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] h-[29px] tracking-normal align-top">Company</x-base.table.td>
                                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->user->company->company_name) && !empty($gsr->user->company->company_name) ? $gsr->user->company->company_name : '') }}</x-base.table.td>
                                                                    </x-base.table.tr>
                                                                    <x-base.table.tr>
                                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 text-[12px] w-[105px] tracking-normal align-top">Address</x-base.table.td>
                                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] h-[60px] align-top">{{ (isset($gsr->user->company->pdf_address) && !empty($gsr->user->company->pdf_address) ? $gsr->user->company->pdf_address : '') }}</x-base.table.td>
                                                                    </x-base.table.tr>
                                                                    <x-base.table.tr>
                                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] tracking-normal align-top">TEL NO.</x-base.table.td>
                                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->user->company->company_phone) && !empty($gsr->user->company->company_phone) ? $gsr->user->company->company_phone : '') }}</x-base.table.td>
                                                                    </x-base.table.tr>
                                                                    <x-base.table.tr>
                                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] tracking-normal align-top">Email</x-base.table.td>
                                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->user->company->company_email) && !empty($gsr->user->company->company_email) ? $gsr->user->company->company_email : '') }}</x-base.table.td>
                                                                    </x-base.table.tr>
                                                                </x-base.table.tbody>
                                                            </x-base.table>
                                                        </x-base.table.td>
                                                    </x-base.table.tr>
                                                </x-base.table.tbody>
                                            </x-base.table>
                                        </x-base.table.td>
                                        <x-base.table.td class="w-[25%] p-0 border-primary align-top">
                                            <x-base.table class="border-none">
                                                <x-base.table.tbody>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] h-[29px] tracking-normal align-top">Name</x-base.table.td>
                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->job->property->occupant_name) && !empty($gsr->job->property->occupant_name) ? $gsr->job->property->occupant_name : '') }}</x-base.table.td>
                                                    </x-base.table.tr>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 text-[12px] w-[105px] tracking-normal align-top">Address</x-base.table.td>
                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] h-[60px] align-top">{{ (isset($gsr->job->property->pdf_address) && !empty($gsr->job->property->pdf_address) ? $gsr->job->property->pdf_address : '') }}</x-base.table.td>
                                                    </x-base.table.tr>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] tracking-normal align-top">Postcode</x-base.table.td>
                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->job->property->postal_code) && !empty($gsr->job->property->postal_code) ? $gsr->job->property->postal_code : '') }}</x-base.table.td>
                                                    </x-base.table.tr>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] tracking-normal align-top">&nbsp;</x-base.table.td>
                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top"></x-base.table.td>
                                                    </x-base.table.tr>
                                                </x-base.table.tbody>
                                            </x-base.table>
                                        </x-base.table.td>
                                        <x-base.table.td class="w-[25%] p-0 border-primary align-top">
                                            <x-base.table class="border-none">
                                                <x-base.table.tbody>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[115px] h-[29px] tracking-normal align-top">Name</x-base.table.td>
                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->customer->full_name) && !empty($gsr->customer->full_name) ? $gsr->customer->full_name : '') }}</x-base.table.td>
                                                    </x-base.table.tr>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[115px] tracking-normal align-top">Company Name</x-base.table.td>
                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->customer->company_name) && !empty($gsr->customer->company_name) ? $gsr->customer->company_name : '') }}</x-base.table.td>
                                                    </x-base.table.tr>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 text-[12px] w-[115px] tracking-normal align-top">Address</x-base.table.td>
                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] h-[60px] align-top">{{ (isset($gsr->customer->pdf_address) && !empty($gsr->customer->pdf_address) ? $gsr->customer->pdf_address : '') }}</x-base.table.td>
                                                    </x-base.table.tr>
                                                    <x-base.table.tr>
                                                        <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[115px] tracking-normal align-top">Postcode</x-base.table.td>
                                                        <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-1 pb-1 text-[13px] align-top">{{ (isset($gsr->customer->postal_code) && !empty($gsr->customer->postal_code) ? $gsr->customer->postal_code : '') }}</x-base.table.td>
                                                    </x-base.table.tr>
                                                </x-base.table.tbody>
                                            </x-base.table>
                                        </x-base.table.td>
                                    </x-base.table.tr>
                                </x-base.table.tbody>
                            </x-base.table>
                        </div>
                        <div class="grid grid-cols-12 gap-x-3 gap-y-0 mt-3">
                            <div class="col-span-12 lg:col-span-9">
                                <x-base.table bordered>
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th colspan="8" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-[11px] uppercase px-2 py-1">
                                                APPLIANCE DETAILS
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center w-[36px]">
                                                #
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center">
                                                Location
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center">
                                                Make
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center">
                                                Model
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center">
                                                Type
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center">
                                                Serial No.
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-normal border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center">
                                                Flue Type
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-normal border-primary bg-primary border-b-0 text-white text-[10px] uppercase px-2 py-1 text-center w-[140px] leading-[1]">
                                                OPERATING PRESSURE (MBAR) OR HEAT INPUT (KW/H OR BTU/H)
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">1</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->location->name) && !empty($gsra1->location->name) ? $gsra1->location->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->make->name) && !empty($gsra1->make->name) ? $gsra1->make->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->model) && !empty($gsra1->model) ? $gsra1->model : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->type->name) && !empty($gsra1->type->name) ? $gsra1->type->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->serial_no) && !empty($gsra1->serial_no) ? $gsra1->serial_no : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->flue->name) && !empty($gsra1->flue->name) ? $gsra1->flue->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[140px]">{{ (isset($gsra1->opt_pressure) && !empty($gsra1->opt_pressure) ? $gsra1->opt_pressure : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">2</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->location->name) && !empty($gsra2->location->name) ? $gsra2->location->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->make->name) && !empty($gsra2->make->name) ? $gsra2->make->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->model) && !empty($gsra2->model) ? $gsra2->model : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->type->name) && !empty($gsra2->type->name) ? $gsra2->type->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->serial_no) && !empty($gsra2->serial_no) ? $gsra2->serial_no : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->flue->name) && !empty($gsra2->flue->name) ? $gsra2->flue->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[140px]">{{ (isset($gsra2->opt_pressure) && !empty($gsra2->opt_pressure) ? $gsra2->opt_pressure : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">3</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->location->name) && !empty($gsra3->location->name) ? $gsra3->location->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->make->name) && !empty($gsra3->make->name) ? $gsra3->make->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->model) && !empty($gsra3->model) ? $gsra3->model : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->type->name) && !empty($gsra3->type->name) ? $gsra3->type->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->serial_no) && !empty($gsra3->serial_no) ? $gsra3->serial_no : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->flue->name) && !empty($gsra3->flue->name) ? $gsra3->flue->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[140px]">{{ (isset($gsra3->opt_pressure) && !empty($gsra3->opt_pressure) ? $gsra3->opt_pressure : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">4</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->location->name) && !empty($gsra4->location->name) ? $gsra4->location->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->make->name) && !empty($gsra4->make->name) ? $gsra4->make->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->model) && !empty($gsra4->model) ? $gsra4->model : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->type->name) && !empty($gsra4->type->name) ? $gsra4->type->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->serial_no) && !empty($gsra4->serial_no) ? $gsra4->serial_no : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->flue->name) && !empty($gsra4->flue->name) ? $gsra4->flue->name : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[140px]">{{ (isset($gsra4->opt_pressure) && !empty($gsra4->opt_pressure) ? $gsra4->opt_pressure : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                                <x-base.table bordered class="mt-3">
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th colspan="10" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-[11px] uppercase px-2 py-1">
                                                FLUE TESTS
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.th rowspan="2" class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center w-[36px]">
                                                #
                                            </x-base.table.th>
                                            <x-base.table.th rowspan="2" class="whitespace-normal border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1.5]">
                                                SAFETY DEVICE(S) CORRECT OPERATION
                                            </x-base.table.th>
                                            <x-base.table.th rowspan="2" class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center">
                                                SPILLAGE TEST
                                            </x-base.table.th>
                                            <x-base.table.th rowspan="2" class="whitespace-normal border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1.5]">
                                                SMOKE PELLET FLUE FLOW TEST
                                            </x-base.table.th>
                                            <x-base.table.th colspan="3" class="whitespace-nowrap border-primary bg-primary border-b-1 border-r-sec border-b-sec text-white text-[10px] uppercase px-2 py-1 text-center leading-[1]">
                                                INITIAL (LOW) COMBUSTION ANALYSER READING
                                            </x-base.table.th>
                                            <x-base.table.th colspan="3" class="whitespace-nowrap border-primary bg-primary border-b-1 border-r-sec border-b-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1]">
                                                FINAL (HIGH) COMBUSTION ANALYSER READING
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1]">
                                                RATIO
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1]">
                                                CO PPM
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1]">
                                                CO2 (%)
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1]">
                                                RATIO
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1]">
                                                CO PPM
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 text-white text-[11px] uppercase px-2 py-1 text-center leading-[1]">
                                                CO2 (%)
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">1</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->safety_devices) && !empty($gsra1->safety_devices) ? $gsra1->safety_devices : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->spillage_test) && !empty($gsra1->spillage_test) ? $gsra1->spillage_test : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->smoke_pellet_test) && !empty($gsra1->smoke_pellet_test) ? $gsra1->smoke_pellet_test : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra1->low_analyser_ratio) && !empty($gsra1->low_analyser_ratio) ? $gsra1->low_analyser_ratio : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra1->low_co) && !empty($gsra1->low_co) ? $gsra1->low_co : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra1->low_co2) && !empty($gsra1->low_co2) ? $gsra1->low_co2 : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra1->high_analyser_ratio) && !empty($gsra1->high_analyser_ratio) ? $gsra1->high_analyser_ratio : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra1->high_co) && !empty($gsra1->high_co) ? $gsra1->high_co : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra1->high_co2) && !empty($gsra1->high_co2) ? $gsra1->high_co2 : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">2</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->safety_devices) && !empty($gsra2->safety_devices) ? $gsra2->safety_devices : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->spillage_test) && !empty($gsra2->spillage_test) ? $gsra2->spillage_test : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->smoke_pellet_test) && !empty($gsra2->smoke_pellet_test) ? $gsra2->smoke_pellet_test : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra2->low_analyser_ratio) && !empty($gsra2->low_analyser_ratio) ? $gsra2->low_analyser_ratio : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra2->low_co) && !empty($gsra2->low_co) ? $gsra2->low_co : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra2->low_co2) && !empty($gsra2->low_co2) ? $gsra2->low_co2 : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra2->high_analyser_ratio) && !empty($gsra2->high_analyser_ratio) ? $gsra2->high_analyser_ratio : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra2->high_co) && !empty($gsra2->high_co) ? $gsra2->high_co : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra2->high_co2) && !empty($gsra2->high_co2) ? $gsra2->high_co2 : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">3</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->safety_devices) && !empty($gsra3->safety_devices) ? $gsra3->safety_devices : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->spillage_test) && !empty($gsra3->spillage_test) ? $gsra3->spillage_test : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->smoke_pellet_test) && !empty($gsra3->smoke_pellet_test) ? $gsra3->smoke_pellet_test : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra3->low_analyser_ratio) && !empty($gsra3->low_analyser_ratio) ? $gsra3->low_analyser_ratio : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra3->low_co) && !empty($gsra3->low_co) ? $gsra3->low_co : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra3->low_co2) && !empty($gsra3->low_co2) ? $gsra3->low_co2 : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra3->high_analyser_ratio) && !empty($gsra3->high_analyser_ratio) ? $gsra3->high_analyser_ratio : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra3->high_co) && !empty($gsra3->high_co) ? $gsra3->high_co : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra3->high_co2) && !empty($gsra3->high_co2) ? $gsra3->high_co2 : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">4</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->safety_devices) && !empty($gsra4->safety_devices) ? $gsra4->safety_devices : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->spillage_test) && !empty($gsra4->spillage_test) ? $gsra4->spillage_test : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->smoke_pellet_test) && !empty($gsra4->smoke_pellet_test) ? $gsra4->smoke_pellet_test : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra4->low_analyser_ratio) && !empty($gsra4->low_analyser_ratio) ? $gsra4->low_analyser_ratio : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra4->low_co) && !empty($gsra4->low_co) ? $gsra4->low_co : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra4->low_co2) && !empty($gsra4->low_co2) ? $gsra4->low_co2 : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra4->high_analyser_ratio) && !empty($gsra4->high_analyser_ratio) ? $gsra4->high_analyser_ratio : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra4->high_co) && !empty($gsra4->high_co) ? $gsra4->high_co : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[90px]">{{ (isset($gsra4->high_co2) && !empty($gsra4->high_co2) ? $gsra4->high_co2 : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                                <x-base.table bordered class="mt-3">
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th colspan="8" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-[11px] uppercase px-2 py-1">
                                                INSPECTION DETAILS
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center w-[36px] leading-[1.5]">
                                                #
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-normal border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1.5]">
                                                FLUE VISUAL <br/>CONDITION
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-normal border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1.5]">
                                                ADEQUATE <br/>VENTILATION
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-normal border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1.5]">
                                                LANDLORD'S <br/>APPLIANCE
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1.5]">
                                                INSPECTED
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-normal border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1.5]">
                                                APPLIANCE <br/>VISUAL CHECK
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-normal border-primary bg-primary border-b-0 border-r-sec text-white text-[11px] uppercase px-2 py-1 text-center leading-[1.5]">
                                                APPLIANCE <br/>SERVICED
                                            </x-base.table.th>
                                            <x-base.table.th class="whitespace-normal border-primary bg-primary border-b-0 text-white text-[11px] uppercase px-2 py-1 text-center w-[140px] leading-[1.5]">
                                                APPLIANCE <br/>SAFE TO USE
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">1</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->flue_visual_condition) && !empty($gsra1->flue_visual_condition) ? $gsra1->flue_visual_condition : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->adequate_ventilation) && !empty($gsra1->adequate_ventilation) ? $gsra1->adequate_ventilation : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->landlord_appliance) && !empty($gsra1->landlord_appliance) ? $gsra1->landlord_appliance : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->inspected) && !empty($gsra1->inspected) ? $gsra1->inspected : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->appliance_visual_check) && !empty($gsra1->appliance_visual_check) ? $gsra1->appliance_visual_check : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra1->appliance_serviced) && !empty($gsra1->appliance_serviced) ? $gsra1->appliance_serviced : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[140px]">{{ (isset($gsra1->appliance_safe_to_use) && !empty($gsra1->appliance_safe_to_use) ? $gsra1->appliance_safe_to_use : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">2</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->flue_visual_condition) && !empty($gsra2->flue_visual_condition) ? $gsra2->flue_visual_condition : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->adequate_ventilation) && !empty($gsra2->adequate_ventilation) ? $gsra2->adequate_ventilation : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->landlord_appliance) && !empty($gsra2->landlord_appliance) ? $gsra2->landlord_appliance : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->inspected) && !empty($gsra2->inspected) ? $gsra2->inspected : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->appliance_visual_check) && !empty($gsra2->appliance_visual_check) ? $gsra2->appliance_visual_check : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra2->appliance_serviced) && !empty($gsra2->appliance_serviced) ? $gsra2->appliance_serviced : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[140px]">{{ (isset($gsra2->appliance_safe_to_use) && !empty($gsra2->appliance_safe_to_use) ? $gsra2->appliance_safe_to_use : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">3</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->flue_visual_condition) && !empty($gsra3->flue_visual_condition) ? $gsra3->flue_visual_condition : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->adequate_ventilation) && !empty($gsra3->adequate_ventilation) ? $gsra3->adequate_ventilation : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->landlord_appliance) && !empty($gsra3->landlord_appliance) ? $gsra3->landlord_appliance : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->inspected) && !empty($gsra3->inspected) ? $gsra3->inspected : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->appliance_visual_check) && !empty($gsra3->appliance_visual_check) ? $gsra3->appliance_visual_check : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra3->appliance_serviced) && !empty($gsra3->appliance_serviced) ? $gsra3->appliance_serviced : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[140px]">{{ (isset($gsra3->appliance_safe_to_use) && !empty($gsra3->appliance_safe_to_use) ? $gsra3->appliance_safe_to_use : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[36px] tracking-normal text-center leading-[1.5]">4</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->flue_visual_condition) && !empty($gsra4->flue_visual_condition) ? $gsra4->flue_visual_condition : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->adequate_ventilation) && !empty($gsra4->adequate_ventilation) ? $gsra4->adequate_ventilation : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->landlord_appliance) && !empty($gsra4->landlord_appliance) ? $gsra4->landlord_appliance : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->inspected) && !empty($gsra4->inspected) ? $gsra4->inspected : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->appliance_visual_check) && !empty($gsra4->appliance_visual_check) ? $gsra4->appliance_visual_check : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5]">{{ (isset($gsra4->appliance_serviced) && !empty($gsra4->appliance_serviced) ? $gsra4->appliance_serviced : '') }}</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-center leading-[1.5] w-[140px]">{{ (isset($gsra4->appliance_safe_to_use) && !empty($gsra4->appliance_safe_to_use) ? $gsra4->appliance_safe_to_use : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                            </div>
                            <div class="col-span-12 lg:col-span-3">
                                <x-base.table bordered>
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th colspan="2" class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-[12px] uppercase px-2 py-1">
                                                GAS INSTALLATION PIPEWORK
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[11px] tracking-normal text-left leading-[1.5]">SATISFACTORY VISUAL INSPECTION</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] w-[110px]">{{ (isset($gsr->satisfactory_visual_inspaction) && !empty($gsr->satisfactory_visual_inspaction) ? $gsr->satisfactory_visual_inspaction : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[11px] tracking-normal text-left leading-[1.5]">EMERGENCY CONTROL ACCESSIBLE</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] w-[110px]">{{ (isset($gsr->emergency_control_accessible) && !empty($gsr->emergency_control_accessible) ? $gsr->emergency_control_accessible : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[11px] tracking-normal text-left leading-[1.5]">SATISFACTORY GAS TIGHTNESS TEST</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] w-[110px]">{{ (isset($gsr->satisfactory_gas_tightness_test) && !empty($gsr->satisfactory_gas_tightness_test) ? $gsr->satisfactory_gas_tightness_test : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[11px] tracking-normal text-left leading-[1.5]">EQUIPOTENTIAL BONDING SATISFACTION</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] w-[110px]">{{ (isset($gsr->equipotential_bonding_satisfactory) && !empty($gsr->equipotential_bonding_satisfactory) ? $gsr->equipotential_bonding_satisfactory : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                                <x-base.table bordered class="mt-3">
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th colspan="2" class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-[12px] uppercase px-2 py-1">
                                                AUDIBLE CO ALARM
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[11px] tracking-normal text-left leading-[1.5]">APPROVED CO ALARMS FITTED</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] w-[110px]">{{ (isset($gsr->co_alarm_fitted) && !empty($gsr->co_alarm_fitted) ? $gsr->co_alarm_fitted : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[11px] tracking-normal text-left leading-[1.5]">ARE CO ALARMS IN DATE</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] w-[110px]">{{ (isset($gsr->co_alarm_in_date) && !empty($gsr->co_alarm_in_date) ? $gsr->co_alarm_in_date : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[11px] tracking-normal text-left leading-[1.5]">TESTING CO ALARMS</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] w-[110px]">{{ (isset($gsr->co_alarm_test_satisfactory) && !empty($gsr->co_alarm_test_satisfactory) ? $gsr->co_alarm_test_satisfactory : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                        <x-base.table.tr>
                                            <x-base.table.td class=" bg-light-2 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[11px] tracking-normal text-left leading-[1.5]">SMOKE ALARMS FITTED</x-base.table.td>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] w-[110px]">{{ (isset($gsr->smoke_alarm_fitted) && !empty($gsr->smoke_alarm_fitted) ? $gsr->smoke_alarm_fitted : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                                <x-base.table bordered class="mt-3">
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-[12px] uppercase px-2 py-1">
                                                GIVE DETAILS OF ANY FAULTS
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] h-[94px] align-top">{{ (isset($gsr->fault_details) && !empty($gsr->fault_details) ? $gsr->fault_details : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                                <x-base.table bordered class="mt-3">
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-[12px] uppercase px-2 py-1">
                                                RECTIFICATION WORK CARRIED OUT
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] h-[94px] align-top">{{ (isset($gsr->rectification_work_carried_out) && !empty($gsr->rectification_work_carried_out) ? $gsr->rectification_work_carried_out : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-x-3 gap-y-0 mt-3">
                            <div class="col-span-12 lg:col-span-3">
                                <x-base.table bordered>
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-[12px] uppercase px-2 py-1">
                                                DETAILS OF WORKS
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] tracking-normal text-left leading-[1.5] h-[112px] align-top">{{ (isset($gsr->details_work_carried_out) && !empty($gsr->details_work_carried_out) ? $gsr->details_work_carried_out : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                                <x-base.table bordered class="border-primary mt-3">
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class="border-primary whitespace-nowrap bg-primary text-white text-[12px] uppercase px-2 py-1.5">HAS FLUE CAP BEEN PUT BACK?</x-base.table.td>
                                            <x-base.table.td class="border-primary whitespace-nowrap text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[130px]">{{ (isset($gsr->flue_cap_put_back) && !empty($gsr->flue_cap_put_back) ? $gsr->flue_cap_put_back : '') }}</x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                            </div>
                            @php 
                                $inspectionDeate = (isset($gsr->inspection_date) && !empty($gsr->inspection_date) ? date('d-m-Y', strtotime($gsr->inspection_date)) : date('d-m-Y'));
                                $nextInspectionDate = (isset($gsr->next_inspection_date) && !empty($gsr->next_inspection_date) ? date('d-m-Y', strtotime($gsr->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
                            @endphp
                            <div class="col-span-12 lg:col-span-9">
                                <x-base.table bordered>
                                    <x-base.table.thead>
                                        <x-base.table.tr>
                                            <x-base.table.th colspan="3" class="whitespace-nowrap border-primary border-b-0 bg-primary text-white text-[11px] uppercase px-2 py-1">
                                                SIGNATURES
                                            </x-base.table.th>
                                        </x-base.table.tr>
                                    </x-base.table.thead>
                                    <x-base.table.tbody>
                                        <x-base.table.tr>
                                            <x-base.table.td class="w-[41%] p-0 border-primary align-top">
                                                <x-base.table class="border-none">
                                                    <x-base.table.tbody>
                                                        <x-base.table.tr>
                                                            <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 text-[12px] w-[105px] tracking-normal align-top">Signature</x-base.table.td>
                                                            <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-[100px] align-top"></x-base.table.td>
                                                        </x-base.table.tr>
                                                        <x-base.table.tr>
                                                            <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] tracking-normal align-top">Issued By</x-base.table.td>
                                                            <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] align-top">{{ (isset($gsr->user->name) && !empty($gsr->user->name) ? $gsr->user->name : '') }}</x-base.table.td>
                                                        </x-base.table.tr>
                                                        <x-base.table.tr>
                                                            <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] tracking-normal align-top">Date of Issue</x-base.table.td>
                                                            <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] align-top">{{ $inspectionDeate }}</x-base.table.td>
                                                            <input type="hidden" name="inspection_date" value="{{ $inspectionDeate }} "/>
                                                        </x-base.table.tr>
                                                    </x-base.table.tbody>
                                                </x-base.table>
                                            </x-base.table.td>
                                            <x-base.table.td class="w-[41%] p-0 border-primary align-top">
                                                <x-base.table class="border-none">
                                                    <x-base.table.tbody>
                                                        <x-base.table.tr>
                                                            <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 text-[12px] w-[105px] tracking-normal align-top">Signature</x-base.table.td>
                                                            <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-[100px] pl-2 pr-2 pt-1 pb-1 align-top">
                                                                @if($signature)
                                                                    <img src="{{ $signature }}" alt="signature" class="h-[80px] w-auto inline-block"/>
                                                                @endif
                                                            </x-base.table.td>
                                                        </x-base.table.tr>
                                                        <x-base.table.tr>
                                                            <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] tracking-normal align-top">Received By</x-base.table.td>
                                                            <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] align-top">{{ (isset($gsr->relation->name) && !empty($gsr->relation->name) ? $gsr->relation->name : '') }}</x-base.table.td>
                                                        </x-base.table.tr>
                                                        <x-base.table.tr>
                                                            <x-base.table.td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] w-[105px] tracking-normal align-top">Print Name</x-base.table.td>
                                                            <x-base.table.td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary text-primary font-medium pl-2 pr-2 pt-1 pb-1 text-[12px] align-top">{{ (isset($gsr->job->property->occupant_name) && !empty($gsr->job->property->occupant_name) ? $gsr->job->property->occupant_name : (isset($gsr->customer->full_name) && !empty($gsr->customer->full_name) ? $gsr->customer->full_name : '')) }}</x-base.table.td>
                                                        </x-base.table.tr>
                                                    </x-base.table.tbody>
                                                </x-base.table>
                                            </x-base.table.td>
                                            <x-base.table.td class="w-[20%] p-0 border-primary align-middle bg-light-2 text-primary text-center px-3">
                                                <x-base.form-label class="text-primary uppercase font-medium text-[12px] leading-none mb-2 px-2">Next Inspection Date</x-base.form-label>
                                                <x-base.form-input name="next_inspection_date" id="next_inspection_date" value="{{ $nextInspectionDate }}" class="inline-block w-full text-center rounded-none h-[35px] font-medium" readonly />
                                            </x-base.table.td>
                                        </x-base.table.tr>
                                    </x-base.table.tbody>
                                </x-base.table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <input id="customer_job_id" name="customer_job_id" type="hidden" value="{{ $gsr->customer_job_id }}" />
            <input id="customer_id" name="customer_id" type="hidden" value="{{ $gsr->customer_id }}" />
            <input id="job_form_id" name="job_form_id" type="hidden" value="{{ $form->id }}" />
            <input id="gsr_id" name="gsr_id" type="hidden" value="{{ $gsr->id }}" />
            <input id="form_slug" name="form_slug" type="hidden" value="{{ $form->slug }}" />
        </div>
    </form>

    @include('app.action-modals')
@endsection
@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
    @vite('resources/css/vendors/tom-select.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/lodash.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/tom-select.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/records/homewoner_gass_safety_record_show.js')
@endPushOnce