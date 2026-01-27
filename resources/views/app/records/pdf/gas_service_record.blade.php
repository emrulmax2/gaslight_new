<html>
    <head>
        <title>{{ $report_title }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
                        *{border-width: 0; border-style: solid;border-color: #e5e7eb;}
                        body{font-family: Tahoma, sans-serif; font-size: 0.875rem; line-height: 1.25rem; color: #475569; padding-top: 0;}
                        table{margin-left: 0px; width: 100%; border-collapse: collapse; text-indent: 0;border-color: inherit;}
                        figure{margin: 0;}
                        @media print{  .no-page-break { page-break-inside: avoid; } .page-break-before { page-break-before: always; } .page-break-after { page-break-after: always; } }
                        @page{margin: .375rem;}

                        .text-center{text-align: center;}
                        .text-left{text-align: left;}
                        .text-right{text-align: right;}
                        .align-top{vertical-align: top;}
                        .align-middle{vertical-align: middle;}

                        .font-medium{font-weight: bold; }
                        .font-bold{font-weight: bold;}
                        .font-sm{font-size: 12px;}
                        .text-2xl{font-size: 1.5rem;}
                        .text-xl{font-size: 1rem;}
                        .text-10px{font-size: 10px;}
                        .text-11px{font-size: 11px;}
                        .text-12px{font-size: 12px;}
                        .text-13px{font-size: 13px;}
                        .text-sm {font-size: 0.875rem;line-height: 1.25rem;}
                        .leading-1{line-height: 1;}
                        .leading-none{line-height: 1;}
                        .leading-20px{line-height: 20px;}
                        .leading-28px{line-height: 28px;}
                        .leading-none {line-height: 1;}
                        .leading-1-3{line-height: 1.3;}
                        .leading-1-2{line-height: 1.2;}
                        .leading-1-1{line-height: 1.1;}
                        .leading-1-5{line-height: 1.5;}
                        .tracking-normal {letter-spacing: 0em;}
                        .text-primary{color: #164e63;}
                        .text-slate-400{color: #94a3b8;}
                        .text-white{color: #FFF;}
                        .uppercase {text-transform: uppercase;}
                        .whitespace-nowrap{white-space: nowrap;}
                        
                        .w-auto{width: auto;}
                        .w-28{width: 7rem;}
                        .w-full{width: 100%;}
                        .w-50-percent, .w-half{width: 50%;}
                        .w-25-percent{width: 25%;}
                        .w-41-percent{width: 41%;}
                        .w-20-percent{width: 20%;}
                        .w-col2{width: 16.666666%;}
                        .w-col4{width: 33.333333%;}
                        .w-col5{width: 41.666666%;}
                        .w-col7{width: 58.333333%;}
                        .w-col8{width: 66.666666%;}
                        .w-col9{width: 75%;}
                        .w-col3{width: 25%;}
                        .w-32 {width: 8rem;}
                        .w-105px{width: 105px;}
                        .w-110px{width: 110px;}
                        .w-115px{width: 115px;}
                        .w-36px{width: 36px;}
                        .w-130px{width: 130px;}
                        .w-140px{width: 140px;}
                        .w-70px{width: 70px;}
                        .w-60px{width: 60px;}
                        .h-auto{height: auto;}
                        .h-29px{height: 29px;}
                        .h-94px{height: 94px;}
                        .h-35px{height: 35px;}
                        .h-60px{height: 60px;}
                        .h-70px{height: 70px;}
                        .h-80px{height: 80px;}
                        .h-100px{height: 100px;}
                        .h-112px{height: 112px;}
                        .h-25px{height: 25px;}
                        .h-45px{height: 45px;}
                        .h-50px{height: 50px;}
                        .h-30px{height: 30px;}
                        .h-83px{height: 83px;}

                        .pt-0{padding-top: 0;}
                        .pr-0{padding-right: 0;}
                        .pb-0{padding-bottom: 0;}
                        .pl-0{padding-left: 0;}
                        .p-0{padding: 0;}
                        .p-25{padding: 0.625rem;}
                        .p-3{padding: 0.75rem;}
                        .p-5{padding: 1.25rem;}
                        .py-05{padding-top: 0.125rem;padding-bottom: 0.125rem;}
                        .py-025{padding-top: 0.0625rem;padding-bottom: 0.0625rem;}
                        .py-1{padding-top: 0.25rem;padding-bottom: 0.25rem;}
                        .py-1-5{padding-top: 0.375rem;padding-bottom: 0.375rem;}
                        .py-2{padding-top: 0.5rem;padding-bottom: 0.5rem;}
                        .py-3{padding-top: 0.75rem;padding-bottom: 0.75rem;}
                        .px-5{padding-left: 1.25rem;padding-right: 1.25rem;}
                        .px-2{padding-left: 0.5rem;padding-right: 0.5rem;}
                        .px-1{padding-left: 0.25rem;padding-right: 0.25rem;}
                        .pt-1{padding-top: 0.25rem;}
                        .pt-1-5{padding-top: 0.375rem;}
                        .pt-2{padding-top: 0.5rem;}
                        .pr-2{padding-right: 0.5rem;}
                        .pr-1{padding-right: 0.25rem;}
                        .pl-1{padding-left: 0.25rem;}
                        .pl-2{padding-left: 0.5rem;}
                        .pb-1{padding-bottom: 0.25rem;}
                        .pb-2{padding-bottom: 0.25rem;}
                        .pt-05{padding-top: 0.125rem;}
                        .pb-05{padding-bottom: 0.125rem;}
                        .mb-05{margin-bottom: 0.25rem;}
                        .mb-1{margin-bottom: 0.5rem;}
                        .mt-1-5{margin-top: 0.375rem;}
                        .mb-2{margin-bottom: 0.5rem;}
                        .mt-2{margin-top: 0.5rem;}
                        .mt-3{margin-top: 0.75rem;}
                        .mt-0{margin-top: 0;}
                        .mb-0{margin-bottom: 0;}
                        .m-2{margin: .5rem;}
                        .mr-1{margin-right: .25rem;}

                        .bg-danger{ background: #b91c1c; }
                        .bg-warning{ background: #f59e0b; }
                        .bg-primary{ background: #164e63; }
                        .bg-white{background: #FFF;}
                        .bg-readonly{ background-color: #f1f5f9;}
                        .bg-light-2{background-color: #D4EFFB;}
                        .bordered{border-width: 1px;}
                        .border-none {border-style: none;}
                        .border-t{border-top-width: 1px;}
                        .border-t-0{border-top-width: 0;}
                        .border-r{border-right-width: 1px;}
                        .border-r-0{border-right-width: 0;}
                        .border-b{border-bottom-width: 1px;}
                        .border-b-0{border-bottom-width: 0;}
                        .border-l{border-left-width: 1px;}
                        .border-0{border-left-width: 0;}
                        .border-b-1{border-bottom-width: 1px;}
                        .border-l-sec{border-left-color: #1d6a87 !important;}
                        .border-r-sec{border-right-color: #1d6a87 !important;}
                        .border-b-sec{border-bottom-color: #1d6a87 !important;}
                        .border-t-sec{border-top-color: #1d6a87 !important;}
                        .border-slate-200 {border-color: #e2e8f0;}
                        .border-primary{border-color: #164e63;}
                        .border-b-white{border-bottom-color: #FFF;}
                        .rounded-none{border-radius: 0px;}
                        
                        .inline-block {display: inline-block;}
                    </style>
    </head>
    @php 
        $appliances = $record->available_options->appliances;
    @endphp
    <body>
        <div class="header bg-primary p-25">
            <table class="grid grid-cols-12 gap-4 items-center">
                <tbody>
                    <tr>
                        <td class="w-col2 align-middle text-center">
                            <label class="text-white uppercase font-medium text-12px leading-none mb-2 inline-block">Certificate Number</label>
                            <div class="inline-block bg-white w-32 text-center rounded-none leading-28px h-35px font-medium text-primary">{{ $record->certificate_number }}</div>
                        </td>
                        <td class="w-col8 text-center align-middle px-5">
                            <h1 class="text-white text-xl leading-none mt-0 mb-05">Service/Maintenance Record</h1>
                            <div class="text-white text-12px leading-1-3">
                                This record can be used to document the outcomes of the checks and tests required by The Gas Safety (Installation and Use) Regulations. 
                                            Some of the outcomes are as a result of visual inspection only and are recorded where appropriate. Unless specifically recorded no detailed 
                                            inspection of the flue lining construction or integrity has been performed.
                                            Registered Business/engineer details can be checked at www.gassaferegister.co.uk or by calling 0800 408 5500
                            </div>
                        </td>
                        <td class="w-col2 align-middle text-right" style="padding-right: 30px;">
                            <img class="w-auto h-80px" src="{{ $logoBase64 }}" alt="Gas Safe Register Logo">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="recordInfo mt-1-5">
            <table class="table table-sm bordered border-primary">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-t-0 border-r-sec text-white text-12px uppercase leading-none px-2 py-1 text-left">
                            COMPANY / INSTALLER
                        </th>
                        <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-t-0 border-r-sec text-white text-12px uppercase leading-none px-2 py-1 text-left">
                            INSPECTION / INSTALLATION ADDRESS
                        </th>
                        <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-t-0 text-white text-12px uppercase leading-none px-2 py-1 text-left">
                            LANDLORD / AGENT / CUSTOMER
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-50-percent p-0 border-primary align-top">
                            <table class="border-none">
                                <tbody>
                                    <tr>
                                        <td class="w-50-percent p-0 border-l-0 border-t-0 border-r-0 border-b-0 align-top">
                                            <table class="border-none">
                                                <tbody>
                                                    <tr>
                                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Engineer</td>
                                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">{{ (isset($record->user->name) && !empty($record->user->name) ? $record->user->name : '') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">GAS SAFE REG.</td>
                                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->user->company->gas_safe_registration_no) && !empty($record->user->company->gas_safe_registration_no) ? $record->user->company->gas_safe_registration_no : '') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">ID CARD NO.</td>
                                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->user->gas_safe_id_card) && !empty($record->user->gas_safe_id_card) ? $record->user->gas_safe_id_card : '') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">&nbsp;</td>
                                                        <td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td class="w-50-percent p-0 border-l-0 border-t-0 border-r-0 border-b-0 align-top">
                                            <table class="border-none">
                                                <tbody>
                                                    <tr>
                                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company</td>
                                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->user->company->company_name) && !empty($record->user->company->company_name) ? $record->user->company->company_name : '') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>
                                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">{{ (isset($record->user->company->pdf_address) && !empty($record->user->company->pdf_address) ? $record->user->company->pdf_address : '') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">TEL NO.</td>
                                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->user->company->company_phone) && !empty($record->user->company->company_phone) ? $record->user->company->company_phone : '') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Email</td>
                                                        <td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->user->company->company_email) && !empty($record->user->company->company_email) ? $record->user->company->company_email : '') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td class="w-25-percent p-0 border-primary align-top">
                            <table class="border-none">
                                <tbody>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Name</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->occupant->occupant_name) && !empty($record->occupant->occupant_name) ? $record->occupant->occupant_name : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">{{ (isset($record->property->pdf_address) && !empty($record->property->pdf_address) ? $record->property->pdf_address : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->property->postal_code) && !empty($record->property->postal_code) ? $record->property->postal_code : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">&nbsp;</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td class="w-25-percent p-0 border-primary align-top">
                            <table class="border-none">
                                <tbody>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Name</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->customer->full_name) && !empty($record->customer->full_name) ? $record->customer->full_name : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Company Name</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px leading-none align-middle">{{ (isset($record->customer->company_name) && !empty($record->customer->company_name) ? $record->customer->company_name : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">{{ (isset($record->billing->pdf_address) && !empty($record->billing->pdf_address) ? $record->billing->pdf_address : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->billing->postal_code) && !empty($record->billing->postal_code) ? $record->billing->postal_code : '') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table class="p-0 border-none mt-1-5">
            <tbody>
                <tr>
                    <td class="w-half pr-1 pl-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="7" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">
                                        Appliance Details
                                    </th>
                                </tr>
                                <tr>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        Location
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        Model
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        Make
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        Type
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        Serial No.
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        GC No.
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        Flue Type
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->appliance_location_id) && !empty($appliances->appliance_location_id) ? locationName($appliances->appliance_location_id) : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->model) && !empty($appliances->model) ? $appliances->model : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->boiler_brand_id) && !empty($appliances->boiler_brand_id) ? boilerBrandName($appliances->boiler_brand_id) : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->appliance_type_id) && !empty($appliances->appliance_type_id) ? typeName($appliances->appliance_type_id) : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->serial_no) && !empty($appliances->serial_no) ? $appliances->serial_no : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->gc_no) && !empty($appliances->gc_no) ? $appliances->gc_no : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->appliance_flue_type_id) && !empty($appliances->appliance_flue_type_id) ? flueName($appliances->appliance_flue_type_id) : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="w-half pl-1 pr-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                @php 
                                    $full_strip_cared_out = (isset($appliances->full_strip_cared_out) && !empty($appliances->full_strip_cared_out) ? $appliances->full_strip_cared_out : '&nbsp;');
                                @endphp
                                <tr>
                                    <th colspan="5" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">
                                        Electronic combustion gas analyser (ECGA) readings
                                    </th>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-10px tracking-normal text-center leading-none border-b border-r w-col2">{!! $full_strip_cared_out !!}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        Initial (low) ECGA reading
                                    </th>
                                    <th colspan="3" class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-center align-middle">
                                        Final (high) ECGA reading
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{!! (isset($appliances->low_analyser_ratio) && !empty($appliances->low_analyser_ratio) ? $appliances->low_analyser_ratio.' Ratio' : '&nbsp;') !!}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{!! (isset($appliances->low_co) && !empty($appliances->low_co) ? $appliances->low_co.' CO (PPM)' : '&nbsp;') !!}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{!! (isset($appliances->low_co2) && !empty($appliances->low_co2) ? $appliances->low_co2.' CO<sub>2</sub> (%)' : '&nbsp;') !!}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{!! (isset($appliances->high_analyser_ratio) && !empty($appliances->high_analyser_ratio) ? $appliances->high_analyser_ratio.' Ratio' : '&nbsp;') !!}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{!! (isset($appliances->high_co) && !empty($appliances->high_co) ? $appliances->high_co.' CO (PPM)' : '&nbsp;') !!}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{!! (isset($appliances->high_co2) && !empty($appliances->high_co2) ? $appliances->high_co2.' CO<sub>2</sub> (%)' : '&nbsp;') !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table class="table table-sm bordered border-primary mt-1-5">
            <thead>
                <tr>
                    <th colspan="5" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">
                        Installation Details
                    </th>
                </tr>
                <tr>
                    <!-- <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Operating Pressure
                    </th> -->
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Rented Accommodation
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Type of Work Carried Out
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Has a gas tightness test been carried out?
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Was the tightness test is a Pass or Fail?
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Is electrical bonding (where required) satisfactory?
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @php 
                        $tightness = (isset($appliances->gas_tightness) && $appliances->gas_tightness == 'Yes' ? true : false);
                    @endphp
                    <!-- <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->opt_pressure) && !empty($appliances->opt_pressure) ? $appliances->opt_pressure : '') }}</td> -->
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->rented_accommodation) && !empty($appliances->rented_accommodation) ? $appliances->rented_accommodation : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->type_of_work_carried_out) && !empty($appliances->type_of_work_carried_out) ? $appliances->type_of_work_carried_out : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->gas_tightness) && !empty($appliances->gas_tightness) ? $appliances->gas_tightness : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ ($tightness && isset($appliances->test_carried_out) && !empty($appliances->test_carried_out) ? $appliances->test_carried_out : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliances->is_electricial_bonding) && !empty($appliances->is_electricial_bonding) ? $appliances->is_electricial_bonding : '') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="p-0 border-none mt-1-5">
            <tbody>
                <tr>
                    <td class="w-half pr-1 pl-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="3" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">
                                        Safety - General
                                    </th>
                                </tr>
                                <tr>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">
                                        Check
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">
                                        Status
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">
                                        Defects found / remedial action taken
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Ventilation correct</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->ventillation) && !empty($appliances->ventillation) ? $appliances->ventillation : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->ventillation_detail) && !empty($appliances->ventillation_detail) ? $appliances->ventillation_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Satisfactory flue flow check</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->satisfactory_flue_flow) && !empty($appliances->satisfactory_flue_flow) ? $appliances->satisfactory_flue_flow : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->satisfactory_flue_flow_detail) && !empty($appliances->satisfactory_flue_flow_detail) ? $appliances->satisfactory_flue_flow_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Satisfactory Flue termination</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->satisfactory_flue_termination) && !empty($appliances->satisfactory_flue_termination) ? $appliances->satisfactory_flue_termination : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->satisfactory_flue_termination_detail) && !empty($appliances->satisfactory_flue_termination_detail) ? $appliances->satisfactory_flue_termination_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Satistactory spillage test</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->satistactory_spillage) && !empty($appliances->satistactory_spillage) ? $appliances->satistactory_spillage : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->satistactory_spillage_detail) && !empty($appliances->satistactory_spillage_detail) ? $appliances->satistactory_spillage_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Safety device(s) correct</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->savety_devices) && !empty($appliances->savety_devices) ? $appliances->savety_devices : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->savety_devices_detail) && !empty($appliances->savety_devices_detail) ? $appliances->savety_devices_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Pipework</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->pipework) && !empty($appliances->pipework) ? $appliances->pipework : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->pipework_detail) && !empty($appliances->pipework_detail) ? $appliances->pipework_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Other (regulations etc.)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->other_regulations) && !empty($appliances->other_regulations) ? $appliances->other_regulations : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->other_regulations_detail) && !empty($appliances->other_regulations_detail) ? $appliances->other_regulations_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Has the installation been carried out to the relevant standard / manufacturers instructions?</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->instruction_followed) && !empty($appliances->instruction_followed) ? $appliances->instruction_followed : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top"></td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Operating Pressure</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">
                                        {{ (isset($appliances->opt_pressure) && !empty($appliances->opt_pressure) ? $appliances->opt_pressure : '') }}
                                        {{ (isset($appliances->opt_pressure_unit) && !empty($appliances->opt_pressure_unit) ? $appliances->opt_pressure_unit : '') }}
                                    </td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="w-half pr-1 pl-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="3" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">
                                        Appliance - Satisfactory
                                    </th>
                                </tr>
                                <tr>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">
                                        Check
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">
                                        Status
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">
                                        Defects found / remedial action taken
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Burner / Injectors</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->burner_injectors) && !empty($appliances->burner_injectors) ? $appliances->burner_injectors : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->burner_injectors_detail) && !empty($appliances->burner_injectors_detail) ? $appliances->burner_injectors_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Ignition and flame picture</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->ignition) && !empty($appliances->ignition) ? $appliances->ignition : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->ignition_detail) && !empty($appliances->ignition_detail) ? $appliances->ignition_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Heat Exchanger</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->heat_exchanger) && !empty($appliances->heat_exchanger) ? $appliances->heat_exchanger : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->heat_exchanger_detail) && !empty($appliances->heat_exchanger_detail) ? $appliances->heat_exchanger_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Electrical connection</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->electrics) && !empty($appliances->electrics) ? $appliances->electrics : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->electrics_detail) && !empty($appliances->electrics_detail) ? $appliances->electrics_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Appliance/ System Controls</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->controls) && !empty($appliances->controls) ? $appliances->controls : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->controls_detail) && !empty($appliances->controls_detail) ? $appliances->controls_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Fans</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->fans) && !empty($appliances->fans) ? $appliances->fans : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->fans_detail) && !empty($appliances->fans_detail) ? $appliances->fans_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Seals (appliance case etc.)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->seals) && !empty($appliances->seals) ? $appliances->seals : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->seals_detail) && !empty($appliances->seals_detail) ? $appliances->seals_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Fireplace catchment space</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->fireplace) && !empty($appliances->fireplace) ? $appliances->fireplace : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->fireplace_detail) && !empty($appliances->fireplace_detail) ? $appliances->fireplace_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Closure plate</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->closure_plate) && !empty($appliances->closure_plate) ? $appliances->closure_plate : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->closure_plate_detail) && !empty($appliances->closure_plate_detail) ? $appliances->closure_plate_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Return air/plenum</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->return_air_ple) && !empty($appliances->return_air_ple) ? $appliances->return_air_ple : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->return_air_ple_detail) && !empty($appliances->return_air_ple_detail) ? $appliances->return_air_ple_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Allowable Location</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->allowable_location) && !empty($appliances->allowable_location) ? $appliances->allowable_location : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->allowable_location_detail) && !empty($appliances->allowable_location_detail) ? $appliances->allowable_location_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Stability</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->stability) && !empty($appliances->stability) ? $appliances->stability : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->stability_detail) && !empty($appliances->stability_detail) ? $appliances->stability_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Working Pressure</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->working_pressure) && !empty($appliances->working_pressure) ? $appliances->working_pressure : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->working_pressure_detail) && !empty($appliances->working_pressure_detail) ? $appliances->working_pressure_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Expansion Vassel checked / recharged?</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->expansion_vassel_checked) && !empty($appliances->expansion_vassel_checked) ? $appliances->expansion_vassel_checked : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->expansion_vassel_checked_detail) && !empty($appliances->expansion_vassel_checked_detail) ? $appliances->expansion_vassel_checked_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Is the Installation and appliance safe to use?</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($appliances->is_safe_to_use) && !empty($appliances->is_safe_to_use) ? $appliances->is_safe_to_use : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($appliances->is_safe_to_use_detail) && !empty($appliances->is_safe_to_use_detail) ? $appliances->is_safe_to_use_detail : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                <tr>
            </tbody>
        </table>

        @php 
            $inspectionDeate = (isset($record->inspection_date) && !empty($record->inspection_date) ? date('d-m-Y', strtotime($record->inspection_date)) : date('d-m-Y'));
            $nextInspectionDate = (isset($record->next_inspection_date) && !empty($record->next_inspection_date) ? date('d-m-Y', strtotime($record->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
        @endphp
        <table class="table table-sm bordered border-primary mt-1-5">
            <thead>
                <tr>
                    <th colspan="3" class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">
                        SIGNATURES
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-41-percent p-0 border-primary align-top border-b-0">
                        <table class="table border-none">
                            <tbody>
                                <tr>
                                    <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-top">Signature</td>
                                    <td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-50px align-top">
                                        @if($userSignBase64)
                                            <img src="{{ $userSignBase64 }}" alt="signature" class="h-50px w-auto inline-block"/>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Issued By</td>
                                    <td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">{{ (isset($record->user->name) && !empty($record->user->name) ? $record->user->name : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Date of Issue</td>
                                    <td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">{{ $inspectionDeate }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="w-41-percent p-0 border-primary align-top border-b-0">
                        <table class="table border-none">
                            <tbody>
                                <tr>
                                    <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-top">Signature</td>
                                    <td class="border-t-0 border-l-0 border-r-0 border-b border-primary h-50px align-top">
                                        @if($signatureBase64)
                                            <img src="{{ $signatureBase64 }}" alt="signature" class="h-50px w-auto inline-block"/>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Received By</td>
                                    <td class="border-t-0 border-l-0 border-r-0 border-b border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">{{ (isset($record->relation->name) && !empty($record->relation->name) ? $record->relation->name : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 py-05 leading-none text-12px w-105px tracking-normal align-middle">Print Name</td>
                                    <td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">{{ (isset($record->received_by) && !empty($record->received_by) ? $record->received_by : (isset($record->customer->full_name) && !empty($record->customer->full_name) ? $record->customer->full_name : '')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="w-20-percent p-0 border-primary border-b-0 align-middle bg-light-2 text-primary text-center px-3">
                        <div class="text-primary uppercase font-medium text-12px leading-none mb-1 px-2">Next Inspection Date</div>
                        <div class="inline-block bg-white w-col9 text-center rounded-none h-30px text-12px font-medium">{{ $nextInspectionDate }}</div>
                    </td>
                </tr>
            </tbody>
        </table>

    </body>
</html>