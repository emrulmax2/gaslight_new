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
            .leading-1-5{line-height: 1.5;}
            .tracking-normal {letter-spacing: 0em;}
            .text-primary{color: #164e63;}
            .text-slate-400{color: #94a3b8;}
            .text-white{color: #FFF;}
            .uppercase {text-transform: uppercase;}
            .whitespace-nowrap{white-space: nowrap;}
            .text-danger{ color: #b91c1c; }
            
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
            .h-30px{height: 30px;}
            .h-29px{height: 29px;}
            .h-94px{height: 94px;}
            .h-35px{height: 35px;}
            .h-60px{height: 60px;}
            .h-70px{height: 70px;}
            .h-80px{height: 80px;}
            .h-100px{height: 100px;}
            .h-112px{height: 112px;}
            .h-25px{height: 25px;}
            .h-40px{height: 40px;}
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
            .mb-005{margin-bottom: 0.125rem;}
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
            .mt-1{margin-top: .25rem;}

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
                            <h1 class="text-white leading-none mt-0 mb-0" style="font-weight: normal; font-size: 24px; margin-top: 5px;">
                                <span class="bg-white inline-block py-1 px-2">
                                    <img src="{{ $palmBase64 }}" alt="stop" style="height: 31px; width: auto;"/>
                                </span><span class="bg-danger inline-block" style="padding: 8.5px 1rem;">
                                    Danger Do Not Use
                                </span><span class="inline-block" style="padding: 8.5px 1rem; background: #FCEA1E; color: #000;">
                                    Gas Warning Notice
                                </span>
                            </h1>
                            <div class="text-white text-12px leading-1-3">
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

        <table class="p-0 border-none mt-1">
            <tbody>
                <tr>
                    <td class="pr-1 pl-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <thead>
                                <tr>
                                    <th colspan="3" class="whitespace-nowrap border-primary border-b-white border-b-1 border-r border-r-sec bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                                        Gas appliances
                                    </th>
                                    <th colspan="7" class="whitespace-nowrap border-primary border-b-white border-b-1 bg-primary text-white text-12px uppercase leading-none px-2 py-1 align-middle text-left">
                                        Defects identified on gas equipment
                                    </th>
                                </tr>
                                <tr>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center w-36px align-middle">
                                        #
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Appliance Type
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Location
                                    </th>

                                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Gas Escape Issue
                                    </th>
                                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Pipework Issue
                                    </th>
                                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Ventilation Issue
                                    </th>
                                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Meter Issue
                                    </th>
                                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Chimney Issue
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Other Issue
                                    </th>
                                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-center align-middle">
                                        Classification
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $serial = 1;
                                @endphp
                                @if(isset($record->available_options->appliances) && !empty($record->available_options->appliances))
                                    @foreach($record->available_options->appliances as $appliance)
                                        <tr>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">{{ $serial }}</td>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliance->appliance_type_id) && $appliance->appliance_type_id > 0 ? typeName($appliance->appliance_type_id) : '') }}</td>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliance->appliance_location_id) && $appliance->appliance_location_id > 0 ? locationName($appliance->appliance_location_id) : '') }}</td>

                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">{{ (isset($appliance->gas_escape_issue) && !empty($appliance->gas_escape_issue) ? $appliance->gas_escape_issue : '') }}</td>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">{{ (isset($appliance->pipework_issue) && !empty($appliance->pipework_issue) ? $appliance->pipework_issue : '') }}</td>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">{{ (isset($appliance->ventilation_issue) && !empty($appliance->ventilation_issue) ? $appliance->ventilation_issue : '') }}</td>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">{{ (isset($appliance->meter_issue) && !empty($appliance->meter_issue) ? $appliance->meter_issue : '') }}</td>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r">{{ (isset($appliance->chimeny_issue) && !empty($appliance->chimeny_issue) ? $appliance->chimeny_issue : '') }}</td>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b border-r">{{ (isset($appliance->other_issue) && $appliance->other_issue == 'Yes' && isset($appliance->other_issue_details) && !empty($appliance->other_issue_details) ? $appliance->other_issue_details : '') }}</td>
                                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-2 border-b">{{ (isset($appliance->gas_warning_classification_id) && $appliance->gas_warning_classification_id > 0 ? classificationName($appliance->gas_warning_classification_id) : '') }}</td>
                                        </tr>
                                        @php 
                                            $serial += 1;
                                        @endphp
                                    @endforeach
                                @endif
                                @for($serial; $serial <= 4; $serial++)
                                <tr>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r">{{ $serial }}</td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r"></td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r"></td>
                                    
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r"></td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r"></td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r"></td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r"></td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r"></td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b border-r"></td>
                                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-5 border-b"></td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table table-sm bordered border-primary mt-1">
            <thead>
                <tr>
                    <th class="whitespace-nowrap border-primary bg-primary border-b-0 border-r border-r-sec text-white text-11px leading-none uppercase px-2 py-05 text-center w-36px align-middle">
                        #
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">
                        Details of faults
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">
                        Actions Required
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-left align-middle">
                        Actions Taken
                    </th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $serial = 1;
                @endphp
                @if(isset($record->available_options->appliances) && !empty($record->available_options->appliances))
                    @foreach($record->available_options->appliances as $appliance)
                        <tr>
                            <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r align-top">{{ $serial }}</td>
                            <td class="w-col4 border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-3 border-b border-r h-35px align-top">{{ (isset($appliance->fault_details) && !empty($appliance->fault_details) ? $appliance->fault_details : '') }}</td>
                            <td class="w-col4 border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-3 border-b border-r h-35px align-top">{{ (isset($appliance->actions_required) && !empty($appliance->actions_required) ? $appliance->actions_required : '') }}</td>
                            <td class="w-col4 border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-3 border-b border-r h-35px align-top">{{ (isset($appliance->action_taken) && !empty($appliance->action_taken) ? $appliance->action_taken : '') }}</td>
                        </tr>
                        @php 
                            $serial += 1;
                        @endphp
                    @endforeach
                @endif
                @for($serial; $serial <= 4; $serial++)
                    <tr>
                        <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px w-36px tracking-normal text-center leading-1-5 border-b border-r align-top">{{ $serial }}</td>
                        <td class="w-col4 border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-3 border-b border-r h-35px align-top"></td>
                        <td class="w-col4 border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-3 border-b border-r h-35px align-top"></td>
                        <td class="w-col4 border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-left leading-1-3 border-b border-r h-35px align-top"></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <table class="p-0 border-none mt-1">
            <tbody>
                <tr>
                    <td class="w-half pr-1 pl-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <tbody>
                                <tr>
                                    <td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-12px px-2 py-1 leading-none align-middle">Reported to HSE under RIDDOR 11(1) (Gas Incident)</td>
                                    <td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-1 text-12px w-130px leading-none align-middle">{{ (isset($record->available_options->otherChecks->reported_to_hse) && !empty($record->available_options->otherChecks->reported_to_hse) ? $record->available_options->otherChecks->reported_to_hse : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="w-half pl-1 pr-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary">
                            <tbody>
                                <tr>
                                    <td class="border-primary whitespace-nowrap font-medium bg-primary text-white text-12px px-2 py-1 leading-none align-middle">Reported to HSE under RIDDOR 11(2) (Dangerous Gas Fitting)</td>
                                    <td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-1 text-12px w-130px leading-none align-middle">{{ (isset($record->available_options->otherChecks->reported_to_hde) && !empty($record->available_options->otherChecks->reported_to_hde) ? $record->available_options->otherChecks->reported_to_hde : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                <tr>
                <tr>
                    <td colspan="2" class="pr-0 pl-0 pb-0 pt-0 align-top">
                        <table class="table table-sm bordered border-primary mt-1-5">
                            <tbody>
                                <tr>
                                    <td class="border-primary whitespace-normal font-medium bg-primary text-white text-12px px-2 py-1 leading-1-2 align-middle">The gas user was not present at the time of this visit and where appropriate, (an IMMEDIATELY DENGEROUS (ID) or AT RISK (AR) solution) the installation has been made safe and this notice left on the premisies.</td>
                                    <td class="border-primary whitespace-nowrap text-primary pl-2 pr-2 py-1 text-12px w-130px leading-none align-middle">{{ (isset($record->available_options->otherChecks->left_on_premisies) && !empty($record->available_options->otherChecks->left_on_premisies) ? $record->available_options->otherChecks->left_on_premisies : '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                <tr>
            </tbody>
        </table>
        <table class="p-0 border-none mt-1">
            <tbody>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r border-t border-l align-top">
                        Where an appliance/installation has been identified as 'Immediately Dangerous' or At Risk, it should not be used until this situation has been resolved.
                        <span class="text-danger"> However, in a limited number of situations, turning off the gas installation will not remove or reduce the risk. In such circumstances the engineer will explain the situation and advice on the necessary course of action to take.</span>
                         See overleaf for information on what to do next
                    </td>
                <tr>
            </tbody>
        </table>

        @php
            $inspectionDeate = (isset($record->inspection_date) && !empty($record->inspection_date) ? date('d-m-Y', strtotime($record->inspection_date)) : date('d-m-Y'));
            $nextInspectionDate = (isset($record->next_inspection_date) && !empty($record->next_inspection_date) ? date('d-m-Y', strtotime($record->next_inspection_date)) : date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate))));
        @endphp
        <table class="table table-sm bordered border-primary mt-1">
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
        <table class="p-0 bordered border-primary mt-1 bg-light-2 ">
            <tbody>
                <tr>
                    <td class="w-half border-r border-primary text-primary pl-2 pr-2 py-1 text-12px tracking-normal text-left leading-1-2 align-top">
                        I confirm that the situations recorded above, have been identified and brought to the attention of 
                        the Responsible Person in accordance with the Gas Safety (Installation and Use) Regulations and Gas Industry
                        Unsafe Situations Procedure.
                    </td>
                    <td class="w-half border-primary text-primary pl-2 pr-2 py-1 text-12px tracking-normal text-left leading-1-2 align-top">
                        I confirm that as the responsible person for this gas installation at the address detailed above I have 
                        been served this Warning Notice. Note: As a gas appliance/installation has been classified as either
                        Immediately Dangerous or At Risk, as detailed above, continued use of the appliance / installation, 
                        after being advised not to do so, many be in breach of the Gas Safety (installation and Use)
                        Regulations.

                    </td>
                <tr>
                <tr>
                    <td colspan="100%" class="w-full border-t border-primary text-primary pl-2 pr-2 py-2 text-12px tracking-normal text-center leading-1-2 align-middle">
                        Contact details of Gas Emergency Service Providers (ESP's) and Gas Suppliers (GS) in the British Isles
                    </td>
                <tr>
            </tbody>
        </table>
        <table class="table table-sm bordered border-primary mt-1">
            <thead>
                <tr>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-center align-middle">
                        Region
                    </th>
                    <th colspan="2" class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-center align-middle">
                        Gas Type
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-center align-middle">
                        Contact Details
                    </th>
                    <th class="whitespace-normal border-primary bg-primary border-b-0 border-r border-r-sec text-white text-12px leading-none uppercase px-2 py-1 text-center align-middle">
                        Telephone Details
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        England, Scotland and Wales
                    </td>
                    <td colspan="2" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Natural Gas
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Contact the Gas Emergency Contact Centr
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        0800 111 999
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        LPG*
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Bulk and Metered supplies
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        See telephone number on the bulk storage vessel or at the meter
                    </td>
                </tr>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Cylinder supplies
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        For cylinder supplies on caravan parks and hire boats, the site owner and/or boat operator may also 
                        have the responsibilities. Advice may be obtained from
                        the gas company identified on the cylinder through their emergency contact details.
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        See gas supplier emergency contact details in the
                        local telephone directory
                    </td>
                </tr>


                <tr>
                    <td rowspan="3" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Northern Ireland
                    </td>
                    <td colspan="2" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Natural Gas
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Northern Ireland Gas Emergency Service
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        0800 002 001
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        LPG*
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Bulk and Metered supplies
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        See telephone number on the bulk storage vessel or at the meter
                    </td>
                </tr>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Cylinder supplies
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        For cylinder supplies on caravan parks and hire boats, the site owner and/or boat operator may also have the responsibilities. Advice may be obtained from
                        the gas company identified on the cylinder through their emergency contact details.
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        See gas supplier emergency contact details in the
                        local telephone directory
                    </td>
                </tr>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Isle of Man
                    </td>
                    <td colspan="2" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Natural gas & LPG *
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Manx Gas Ltd.
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        0808 1624 444
                    </td>
                </tr>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Channel Islands - Guernsey
                    </td>
                    <td colspan="2" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Mains gas & LPG *
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Contact Guernsey Gas Ltd.
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        01481 749000
                    </td>
                </tr>
                <tr>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Channel Islands - Jersey
                    </td>
                    <td colspan="2" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Mains gas & LPG *
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Contact Jersey Gas Company Ltd
                    </td>
                    <td class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        01534 755555
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="border-primary text-primary pl-2 pr-2 py-05 text-12px tracking-normal text-center leading-1-3 border-b border-r align-middle">
                        Mains gas in the Channel Islands is an LPG and air mixture LPG - Liquefied Petroleum Gas
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="p-0 table-sm bordered border-primary mt-1 bg-light-2">
            <tbody>
                <tr>
                    <td class="w-half border-primary text-primary pl-2 pr-2 py-1 text-12px tracking-normal text-left leading-1-2 align-top">
                        <div class="font-bold mb-05">GAS SAFE CONTACT DETAILS</div>
                        <div class="mb-005">Gas Safe Register</div>
                        <div class="mb-005">PO Box 6804</div>
                        <div class="mb-005">Basingstoke,</div>
                        <div class="mb-005">RG24 4NB</div>
                        <div class="mb-005">0800 408 5500</div>
                    </td>
                    <td class="w-half border-primary text-primary pl-2 pr-2 py-1 text-12px tracking-normal text-left leading-1-2 align-top">
                        <div class="font-bold mb-05">DEFINITIONS</div>
                        <div class="mb-1">
                            <strong>IMMEDIATELY DANGEROUS (ID)</strong> - It is a dangerous appliance/installation, which if left connected to a gas supply 
                            <strong> is an immediate danger to life or property</strong>. Examples of this are combustion products entering the room, and gas escapes.
                        </div>
                        <div>
                            <strong>AT RISK (AR)</strong> - Is a potentially dangerous appliance/installation where one or more faults exist and which, as a result 
                            <strong>may in the future constitute a danger</strong> to life or property. An example of this is inadequate ventilation
                        </div>
                    </td>
                <tr>
            </tbody>
        </table>
    </body>
</html>