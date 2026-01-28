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
                            <h1 class="text-white text-2xl leading-none mt-0 mb-0">Gas Boiler System Commissioning Checklist</h1>
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

        <table class="table table-sm bordered border-primary mt-1-5">
            <thead>
                <tr>
                    <th colspan="6" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                        Appliance Details
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left font-medium leading-1-2 border-b border-r">Make</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->boiler_brand_id) && !empty($record->available_options->appliances->boiler_brand_id) ? boilerBrandName($record->available_options->appliances->boiler_brand_id) : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left font-medium leading-1-2 border-b border-r">Model</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->model) && !empty($record->available_options->appliances->model) ? $record->available_options->appliances->model : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left font-medium leading-1-2 border-b border-r">Serial No</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->serial_no) && !empty($record->available_options->appliances->serial_no) ? $record->available_options->appliances->serial_no : '') }}</td>
                </tr>
            </tbody>
        </table>
        <table class="p-0 border-none mt-1-5">
            <tbody>
                <tr>
                    <td class="w-col4 pr-1 pl-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                                        Controls
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Time and temp control to heating</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->appliance_time_temperature_heating_id) && !empty($record->available_options->appliances->appliance_time_temperature_heating_id) ? heatingName($record->available_options->appliances->appliance_time_temperature_heating_id) : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Time and temp control to hot water</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->tmp_control_hot_water) && !empty($record->available_options->appliances->tmp_control_hot_water) ? $record->available_options->appliances->tmp_control_hot_water : '') }}</td>
                                </tr>
                                <tr>    
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Heating zone valves</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->heating_zone_vlv) && !empty($record->available_options->appliances->heating_zone_vlv) ? $record->available_options->appliances->heating_zone_vlv : '') }}</td>
                                </tr>
                                <tr>    
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Hot water zone valves</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->hot_water_zone_vlv) && !empty($record->available_options->appliances->hot_water_zone_vlv) ? $record->available_options->appliances->hot_water_zone_vlv : '') }}</td>
                                </tr>
                                <tr>     
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Thermostatic radiator valves</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->therm_radiator_vlv) && !empty($record->available_options->appliances->therm_radiator_vlv) ? $record->available_options->appliances->therm_radiator_vlv : '') }}</td>
                                </tr>
                                <tr> 
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Automatic bypass to system</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->bypass_to_system) && !empty($record->available_options->appliances->bypass_to_system) ? $record->available_options->appliances->bypass_to_system : '') }}</td>
                                </tr>
                                <tr>     
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Boiler interlock</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->boiler_interlock) && !empty($record->available_options->appliances->boiler_interlock) ? $record->available_options->appliances->boiler_interlock : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                                        Combination boilers only
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $is_in_hard_water_area = (isset($record->available_options->appliances->is_in_hard_water_area) && !empty($record->available_options->appliances->is_in_hard_water_area) ? $record->available_options->appliances->is_in_hard_water_area : '');
                                    $is_scale_reducer_fitted = (isset($record->available_options->appliances->is_scale_reducer_fitted) && !empty($record->available_options->appliances->is_scale_reducer_fitted) ? $record->available_options->appliances->is_scale_reducer_fitted : '');
                                @endphp
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">is the installation in a hard water area (above 200ppm)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ $is_in_hard_water_area }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">If required by the manufacturer, has the water scale reducer been fitted</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ ($is_in_hard_water_area == 'Yes' ? $is_scale_reducer_fitted : '') }}</td>
                                </tr>
                                <tr>    
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">What type of scale reducer has been fitted</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ ($is_in_hard_water_area == 'Yes' && $is_scale_reducer_fitted == 'Yes' && isset($record->available_options->appliances->what_reducer_fitted) && !empty($record->available_options->appliances->what_reducer_fitted) ? $record->available_options->appliances->what_reducer_fitted : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="w-col4 pl-1 pr-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                                        All Systems
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">The system has been flushed and cleaned in accordance with BS7593 and boiler manufacturer\'s instructions</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->flushed_and_cleaned) && !empty($record->available_options->appliances->flushed_and_cleaned) ? $record->available_options->appliances->flushed_and_cleaned : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">What cleaner was used</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->clearner_name) && !empty($record->available_options->appliances->clearner_name) ? $record->available_options->appliances->clearner_name : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">What inhibitor was used</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->inhibitor_quantity) && !empty($record->available_options->appliances->inhibitor_quantity) ? 'Quantity: '. $record->available_options->appliances->inhibitor_quantity : '').(isset($record->available_options->appliances->inhibitor_amount) && !empty($record->available_options->appliances->inhibitor_amount) ? ' Liters: '. $record->available_options->appliances->inhibitor_amount : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Has a primary water system filter been installed</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->primary_ws_filter_installed) && !empty($record->available_options->appliances->primary_ws_filter_installed) ? $record->available_options->appliances->primary_ws_filter_installed : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                                        Domestic hot water mode
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Gas Rate</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">
                                        {{ (isset($record->available_options->appliances->dom_gas_rate) && !empty($record->available_options->appliances->dom_gas_rate) ? $record->available_options->appliances->dom_gas_rate : '') }}
                                        {{ (isset($record->available_options->appliances->dom_gas_rate_unit) && !empty($record->available_options->appliances->dom_gas_rate_unit) ? $record->available_options->appliances->dom_gas_rate_unit : '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Operating pressure (or inlet pressure) at maximum rate </td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">
                                        {{ (isset($record->available_options->appliances->dom_burner_opt_pressure) && !empty($record->available_options->appliances->dom_burner_opt_pressure) ? $record->available_options->appliances->dom_burner_opt_pressure : '') }}
                                        {{ (isset($record->available_options->appliances->dom_burner_opt_pressure_unit) && !empty($record->available_options->appliances->dom_burner_opt_pressure_unit) ? $record->available_options->appliances->dom_burner_opt_pressure_unit : '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Cold water inlet temperature (°C)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->dom_cold_water_temp) && !empty($record->available_options->appliances->dom_cold_water_temp) ? $record->available_options->appliances->dom_cold_water_temp : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Hot water has been checked at all outlets</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->dom_checked_outlet) && !empty($record->available_options->appliances->dom_checked_outlet) ? $record->available_options->appliances->dom_checked_outlet : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Water flow rate (l/min)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->dom_water_flow_rate) && !empty($record->available_options->appliances->dom_water_flow_rate) ? $record->available_options->appliances->dom_water_flow_rate : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">&nbsp;</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="w-col4 pl-1 pr-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                                        Central heating mode
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Gas Rate </td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">
                                        {{ (isset($record->available_options->appliances->gas_rate) && !empty($record->available_options->appliances->gas_rate) ? $record->available_options->appliances->gas_rate : '') }}
                                        {{ (isset($record->available_options->appliances->gas_rate_unit) && !empty($record->available_options->appliances->gas_rate_unit) ? $record->available_options->appliances->gas_rate_unit : '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Central heating output left at factory setting</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->cho_factory_setting) && !empty($record->available_options->appliances->cho_factory_setting) ? $record->available_options->appliances->cho_factory_setting : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Operating pressure (or inlet pressure) at maximum rate </td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">
                                        {{ (isset($record->available_options->appliances->burner_opt_pressure) && !empty($record->available_options->appliances->burner_opt_pressure) ? $record->available_options->appliances->burner_opt_pressure : '') }}
                                        {{ (isset($record->available_options->appliances->burner_opt_pressure_unit) && !empty($record->available_options->appliances->burner_opt_pressure_unit) ? $record->available_options->appliances->burner_opt_pressure_unit : '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Central heating flow temperature (°C)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->centeral_heat_flow_temp) && !empty($record->available_options->appliances->centeral_heat_flow_temp) ? $record->available_options->appliances->centeral_heat_flow_temp : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Central heating return temperature (°C)</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->centeral_heat_return_temp) && !empty($record->available_options->appliances->centeral_heat_return_temp) ? $record->available_options->appliances->centeral_heat_return_temp : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="2" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                                        Condensing boilers only
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">The condensate drain has been installed in accordance with the manufacturer\'s instructions and/or BS5546/BS6798</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->con_drain_installed) && !empty($record->available_options->appliances->con_drain_installed) ? $record->available_options->appliances->con_drain_installed : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Point of Termination</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->point_of_termination) && !empty($record->available_options->appliances->point_of_termination) ? $record->available_options->appliances->point_of_termination : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">Method of Disposal</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4">{{ (isset($record->available_options->appliances->dispsal_method) && !empty($record->available_options->appliances->dispsal_method) ? $record->available_options->appliances->dispsal_method : '') }}</td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">&nbsp;</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4"></td>
                                </tr>
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col8">&nbsp;</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r w-col4"></td>
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
                    <th colspan="10" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                        All installations
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Min. ratio</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">CO at min. rate</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">CO/CO at min. rate</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">Max. ratio</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">CO at max. rate</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">CO/CO at max. rate</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">The heating and hot water system complies with the appropriate Building Regulations</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">The boiler and associated products have been installed and commissioned in accordance with the manufacturer\'s instructions</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">The operation of the boiler system controls have been demonstrated to and understood by the customer</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">The manufacturer\'s literature, including Benchmark Checklist and Service Record, has been explained and left with the customer</td>
                </tr>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->min_ratio) && !empty($record->available_options->appliances->min_ratio) ? $record->available_options->appliances->min_ratio : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->min_co) && !empty($record->available_options->appliances->min_co) ? $record->available_options->appliances->min_co : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->min_co2) && !empty($record->available_options->appliances->min_co2) ? $record->available_options->appliances->min_co2 : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->max_ratio) && !empty($record->available_options->appliances->max_ratio) ? $record->available_options->appliances->max_ratio : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->max_co) && !empty($record->available_options->appliances->max_co) ? $record->available_options->appliances->max_co : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->max_co2) && !empty($record->available_options->appliances->max_co2) ? $record->available_options->appliances->max_co2 : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->app_building_regulation) && !empty($record->available_options->appliances->app_building_regulation) ? $record->available_options->appliances->app_building_regulation : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->commissioned_man_ins) && !empty($record->available_options->appliances->commissioned_man_ins) ? $record->available_options->appliances->commissioned_man_ins : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->demonstrated_understood) && !empty($record->available_options->appliances->demonstrated_understood) ? $record->available_options->appliances->demonstrated_understood : '') }}</td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-2 border-b border-r">{{ (isset($record->available_options->appliances->literature_including) && !empty($record->available_options->appliances->literature_including) ? $record->available_options->appliances->literature_including : '') }}</td>
                </tr>
            </tbody>
        </table>

        @php 
            $inspectionDeate = (isset($record->inspection_date) && !empty($record->inspection_date) ? date('d-m-Y', strtotime($record->inspection_date)) : date('d-m-Y'));
            $nextInspectionDate = (isset($record->next_inspection_date) && !empty($record->next_inspection_date) ? date('d-m-Y', strtotime($record->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
        @endphp
        <table class="table table-sm bordered border-primary mt-1-5">
            <thead>
                <tr>
                    <th colspan="{{ ($record->available_options->appliances->is_next_inspection == 'Applicable' ? '3' : '2') }}" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                        SIGNATURES
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="w-{{ ($record->available_options->appliances->is_next_inspection == 'Applicable' ? '41-percent' : 'half') }} p-0 border-primary align-top border-b-0">
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
                    <td class="w-{{ ($record->available_options->appliances->is_next_inspection == 'Applicable' ? '41-percent' : 'half') }} p-0 border-primary align-top border-b-0">
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
                                    <td class="border-t-0 border-l-0 border-r-0 border-b-0 border-primary font-medium pl-2 pr-2 pt-1 pb-1 text-12px leading-none align-middle">{{ (isset($record->received_by) && !empty($record->received_by) ? $record->received_by : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    @if($record->available_options->appliances->is_next_inspection == 'Applicable')
                        <td class="w-20-percent p-0 border-primary border-b-0 align-middle bg-light-2 text-primary text-center px-3">
                            <div class="text-primary uppercase font-medium text-12px leading-none mb-1 px-2">Next Inspection Date</div>
                            <div class="inline-block bg-white w-col9 text-center rounded-none h-30px text-12px font-medium">{{ $nextInspectionDate }}</div>
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>

    </body>
</html>