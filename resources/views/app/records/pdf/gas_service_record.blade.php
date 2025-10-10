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
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->job->property->occupant_name) && !empty($record->job->property->occupant_name) ? $record->job->property->occupant_name : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-05 pb-05 text-12px w-110px tracking-normal leading-1-3 align-top">Address</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">{{ (isset($record->job->property->pdf_address) && !empty($record->job->property->pdf_address) ? $record->job->property->pdf_address : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->job->property->postal_code) && !empty($record->job->property->postal_code) ? $record->job->property->postal_code : '') }}</td>
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
                                        <td class="border-t-0 border-l-0 border-r-0 border-b border-primary pl-2 pr-2 pt-1 pb-05 text-12px h-45px leading-1-3 align-top">{{ (isset($record->customer->pdf_address) && !empty($record->customer->pdf_address) ? $record->customer->pdf_address : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="uppercase border-t-0 border-l-0 border-r-0 border-b-0 border-primary bg-light-2 text-primary font-medium pl-2 pr-2 pt-0 pb-0 text-12px w-110px h-25px tracking-normal leading-1-3 align-middle">Postcode</td>
                                        <td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary pl-2 pr-2 pt-05 pb-05 text-12px leading-none align-middle">{{ (isset($record->customer->postal_code) && !empty($record->customer->postal_code) ? $record->customer->postal_code : '') }}</td>
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
                                    <th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->appliance_location_id) && !empty($record->available_options->appliances->appliance_location_id) ? locationName($record->available_options->appliances->appliance_location_id) : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->model) && !empty($record->available_options->appliances->model) ? $record->available_options->appliances->model : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->boiler_brand_id) && !empty($record->available_options->appliances->boiler_brand_id) ? boilerBrandName($record->available_options->appliances->boiler_brand_id) : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->appliance_type_id) && !empty($record->available_options->appliances->appliance_type_id) ? typeName($record->available_options->appliances->appliance_type_id) : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->serial_no) && !empty($record->available_options->appliances->serial_no) ? $record->available_options->appliances->serial_no : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->gc_no) && !empty($record->available_options->appliances->gc_no) ? $record->available_options->appliances->gc_no : '') }}</td>
                                </tr>$record->available_options->appliances
                            </tbody>
                        </table>
                    </td>
                    <td class="w-half pl-1 pr-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">
                                        Electronic Combustion Gas Analyser Readings
                                    </th>
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
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{{ (isset($record->available_options->appliances->low_analyser_ratio) && !empty($record->available_options->appliances->low_analyser_ratio) ? $record->available_options->appliances->low_analyser_ratio.' Ratio' : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{{ (isset($record->available_options->appliances->low_co) && !empty($record->available_options->appliances->low_co) ? $record->available_options->appliances->low_co.' CO (PPM)' : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{!! (isset($record->available_options->appliances->low_co2) && !empty($record->available_options->appliances->low_co2) ? $record->available_options->appliances->low_co2.' CO<sub>2</sub> (%)' : '') !!}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{{ (isset($record->available_options->appliances->high_analyser_ratio) && !empty($record->available_options->appliances->high_analyser_ratio) ? $record->available_options->appliances->high_analyser_ratio.' Ratio' : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{{ (isset($record->available_options->appliances->high_co) && !empty($record->available_options->appliances->high_co) ? $record->available_options->appliances->high_co.' CO (PPM)' : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r w-col2">{!! (isset($record->available_options->appliances->high_co2) && !empty($record->available_options->appliances->high_co2) ? $record->available_options->appliances->high_co2.' CO<sub>2</sub> (%)' : '') !!}</td>
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
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Operating Pressure
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Rented Accommodation
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Type of Work Carried Out
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        If a gas test has been carried out, was this a pass or fail
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-10px leading-1-2 uppercase px-2 py-05 text-center align-middle">
                        Is electrical bonding (where required satisfactory)
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->opt_pressure) && !empty($record->available_options->appliances->opt_pressure) ? $record->available_options->appliances->opt_pressure : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->rented_accommodation) && !empty($record->available_options->appliances->rented_accommodation) ? $record->available_options->appliances->rented_accommodation : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->type_of_work_carried_out) && !empty($record->available_options->appliances->type_of_work_carried_out) ? $record->available_options->appliances->type_of_work_carried_out : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->test_carried_out) && !empty($record->available_options->appliances->test_carried_out) ? $record->available_options->appliances->test_carried_out : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->is_electricial_bonding) && !empty($record->available_options->appliances->is_electricial_bonding) ? $record->available_options->appliances->is_electricial_bonding : '') }}</td>
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
                                        Appliance Checks
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
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Heat Exchanger</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->heat_exchanger) && !empty($record->available_options->appliances->heat_exchanger) ? $record->available_options->appliances->heat_exchanger : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->heat_exchanger_detail) && !empty($record->available_options->appliances->heat_exchanger_detail) ? $record->available_options->appliances->heat_exchanger_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Burner / injectors</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->burner_injectors) && !empty($record->available_options->appliances->burner_injectors) ? $record->available_options->appliances->burner_injectors : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->burner_injectors_detail) && !empty($record->available_options->appliances->burner_injectors_detail) ? $record->available_options->appliances->burner_injectors_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Flame Picture</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->flame_picture) && !empty($record->available_options->appliances->flame_picture) ? $record->available_options->appliances->flame_picture : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->flame_picture_detail) && !empty($record->available_options->appliances->flame_picture_detail) ? $record->available_options->appliances->flame_picture_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Ignition</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->ignition) && !empty($record->available_options->appliances->ignition) ? $record->available_options->appliances->ignition : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->ignition_detail) && !empty($record->available_options->appliances->ignition_detail) ? $record->available_options->appliances->ignition_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Electrical Connection</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->electrics) && !empty($record->available_options->appliances->electrics) ? $record->available_options->appliances->electrics : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->electrics_detail) && !empty($record->available_options->appliances->electrics_detail) ? $record->available_options->appliances->electrics_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Appliance / System Controls</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->controls) && !empty($record->available_options->appliances->controls) ? $record->available_options->appliances->controls : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->controls_detail) && !empty($record->available_options->appliances->controls_detail) ? $record->available_options->appliances->controls_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Leaks gas / water</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->leak_gas_water) && !empty($record->available_options->appliances->leak_gas_water) ? $record->available_options->appliances->leak_gas_water : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->leak_gas_water_detail) && !empty($record->available_options->appliances->leak_gas_water_detail) ? $record->available_options->appliances->leak_gas_water_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Seals (appliance case etc.)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->seals) && !empty($record->available_options->appliances->seals) ? $record->available_options->appliances->seals : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->seals_detail) && !empty($record->available_options->appliances->seals_detail) ? $record->available_options->appliances->seals_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Pipework</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->pipework) && !empty($record->available_options->appliances->pipework) ? $record->available_options->appliances->pipework : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->pipework_detail) && !empty($record->available_options->appliances->pipework_detail) ? $record->available_options->appliances->pipework_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Fans</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->fans) && !empty($record->available_options->appliances->fans) ? $record->available_options->appliances->fans : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->fans_detail) && !empty($record->available_options->appliances->fans_detail) ? $record->available_options->appliances->fans_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Fireplace catchment space</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->fireplace) && !empty($record->available_options->appliances->fireplace) ? $record->available_options->appliances->fireplace : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->fireplace_detail) && !empty($record->available_options->appliances->fireplace_detail) ? $record->available_options->appliances->fireplace_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Closure Plate & PRS10 Tape</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->closure_plate) && !empty($record->available_options->appliances->closure_plate) ? $record->available_options->appliances->closure_plate : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->closure_plate_detail) && !empty($record->available_options->appliances->closure_plate_detail) ? $record->available_options->appliances->closure_plate_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Allowable Location</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->allowable_location) && !empty($record->available_options->appliances->allowable_location) ? $record->available_options->appliances->allowable_location : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->fireplace_allowable_location_detail) && !empty($record->available_options->appliances->fireplace_allowable_location_detail) ? $record->available_options->appliances->fireplace_allowable_location_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Boiler Ratio</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->boiler_ratio) && !empty($record->available_options->appliances->boiler_ratio) ? $record->available_options->appliances->boiler_ratio : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->boiler_ratio_detail) && !empty($record->available_options->appliances->boiler_ratio_detail) ? $record->available_options->appliances->boiler_ratio_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Stability</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->stability) && !empty($record->available_options->appliances->stability) ? $record->available_options->appliances->stability : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->stability_detail) && !empty($record->available_options->appliances->stability_detail) ? $record->available_options->appliances->stability_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Return air / Plenum</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->return_air_ple) && !empty($record->available_options->appliances->return_air_ple) ? $record->available_options->appliances->return_air_ple : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->return_air_ple_detail) && !empty($record->available_options->appliances->return_air_ple_detail) ? $record->available_options->appliances->return_air_ple_detail : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="w-half pr-1 pl-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="3" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-10px uppercase leading-none px-2 py-05 align-middle text-left">
                                        Safety Checks
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
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Ventilation</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->ventillation) && !empty($record->available_options->appliances->ventillation) ? $record->available_options->appliances->ventillation : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->ventillation_detail) && !empty($record->available_options->appliances->ventillation_detail) ? $record->available_options->appliances->ventillation_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Flue Termination</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->flue_termination) && !empty($record->available_options->appliances->flue_termination) ? $record->available_options->appliances->flue_termination : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->flue_termination_detail) && !empty($record->available_options->appliances->flue_termination_detail) ? $record->available_options->appliances->flue_termination_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Smoke Pellet Flue Flow Test</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->smoke_pellet_flue_flow) && !empty($record->available_options->appliances->smoke_pellet_flue_flow) ? $record->available_options->appliances->smoke_pellet_flue_flow : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->smoke_pellet_flue_flow_detail) && !empty($record->available_options->appliances->smoke_pellet_flue_flow_detail) ? $record->available_options->appliances->smoke_pellet_flue_flow_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Smoke Match Spillage Test</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->smoke_pellet_spillage) && !empty($record->available_options->appliances->smoke_pellet_spillage) ? $record->available_options->appliances->smoke_pellet_spillage : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->smoke_pellet_spillage_detail) && !empty($record->available_options->appliances->smoke_pellet_spillage_detail) ? $record->available_options->appliances->smoke_pellet_spillage_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Working Pressure</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->working_pressure) && !empty($record->available_options->appliances->working_pressure) ? $record->available_options->appliances->working_pressure : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->working_pressure_detail) && !empty($record->available_options->appliances->working_pressure_detail) ? $record->available_options->appliances->working_pressure_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Safety Device</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->savety_devices) && !empty($record->available_options->appliances->savety_devices) ? $record->available_options->appliances->savety_devices : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->savety_devices_detail) && !empty($record->available_options->appliances->savety_devices_detail) ? $record->available_options->appliances->savety_devices_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Gas Tightness Test</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->gas_tightness) && !empty($record->available_options->appliances->gas_tightness) ? $record->available_options->appliances->gas_tightness : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->gas_tightness_detail) && !empty($record->available_options->appliances->gas_tightness_detail) ? $record->available_options->appliances->gas_tightness_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Expansion vessel checked / rech rged</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->expansion_vassel_checked) && !empty($record->available_options->appliances->expansion_vassel_checked) ? $record->available_options->appliances->expansion_vassel_checked : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->expansion_vassel_checked_detail) && !empty($record->available_options->appliances->expansion_vassel_checked_detail) ? $record->available_options->appliances->expansion_vassel_checked_detail : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-115px align-top">Other (regulations etc.)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-10px tracking-normal text-left leading-1-1 border-b border-r w-60px align-top">{{ (isset($record->available_options->appliances->other_regulations) && !empty($record->available_options->appliances->other_regulations) ? $record->available_options->appliances->other_regulations : '') }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-025 text-11px tracking-normal text-left leading-1-1 border-b border-r align-top">{{ (isset($record->available_options->appliances->other_regulations_detail) && !empty($record->available_options->appliances->other_regulations_detail) ? $record->available_options->appliances->other_regulations_detail : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm bordered border-primary mt-1-5">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap border-primary border-b-1 bg-primary text-white text-10px leading-none uppercase px-2 py-05 text-left align-middle">
                                        Necessary remedial work required
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-11px tracking-normal text-left leading-1-3 align-top h-60px">{{ (isset($record->available_options->appliances->work_required_note) && !empty($record->available_options->appliances->work_required_note) ? $record->available_options->appliances->work_required_note : '') }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                <tr>
            </tbody>
        </table>

        <table class="p-0 border-none mt-1-5">
            <tbody>
                <tr>
                    <td class="w-col5 pr-1 pl-0 pb-0 pt-0 align-top">
                    <table class="table table-sm bordered border-primary">
                        <tbody>
                            <tr>
                                <td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-10px uppercase px-2 py-05 leading-none align-middle">Is the installation and appliance safe to use?</td>
                                <td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-10px w-130px leading-none align-middle">{{ (isset($record->available_options->appliances->is_safe_to_use) && !empty($record->available_options->appliances->is_safe_to_use) ? $record->available_options->appliances->is_safe_to_use : '') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                    <td class="w-col7 pl-1 pr-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <tbody>
                                <tr>
                                    <td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-10px uppercase px-2 py-05 leading-none align-middle">Has the installation been carried out to the relevant standard/manufacturer\'s instructions? </td>
                                    <td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-05 text-10px w-130px leading-none align-middle">{{ (isset($record->available_options->appliances->instruction_followed) && !empty($record->available_options->appliances->instruction_followed) ? $record->available_options->appliances->instruction_followed : '') }}</td>
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