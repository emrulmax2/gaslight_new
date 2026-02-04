<html>
    <head>
        <title>{{ $report_title }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            body{
                /* font-family: Tahoma, sans-serif; font-size: 14px; line-height: 20px; color: #475569; padding-top: 0; */
                font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
                color: #333;
                font-size: 14px;
                line-height: 1.5;
                margin: 0;
                padding: 0;
                background-color: #fff;
            }
            @page{margin: 20px 0 20px;}
            header{position: fixed;left: 0px;right: 0px;top: -20px;height: 20px; background: #4A4A4A;}
            footer{position: fixed;left: 0px;right: 0px;bottom: -2px;height: 20px;}
            footer p{margin: 0 0 5px; font-size: 10px; line-height: 1; text-align: center;}

            table{margin-left: 0px; width: 100%; border-collapse: collapse;}
            figure{margin: 0;}
            .text-center{text-align: center;}
            .text-left{text-align: left;}
            .text-right{text-align: right;}
            @media print{ .pageBreak{page-break-after: always;} }
            
            .pageBreak{page-break-after: always;}
            .font-medium{font-weight: bold; }
            .font-bold{font-weight: bold;}
            .font-normal{font-weight: normal;}
            .v-top{vertical-align: top;}
            .text-primary{color: #164e63;}
            .font-sm{font-size: 12px;}
            .text-slate-400{color: #94a3b8;}
            .uppercase{text-transform: uppercase;}
            
            .mr-3{margin-right: 3px;}
            .mr-1{margin-right: 4px;}
            .pt-10{padding-top: 10px;}
            .pt-9{padding-top: 9px;}
            .mt-5{margin-top: 5px;}
            .mb-3{margin-bottom: 3px;}
            .mb-4{margin-bottom: 4px;}
            .mb-1{margin-bottom: 1px;}
            .mb-8{margin-bottom: 8px;}
            .mb-5{margin-bottom: 5px;}
            .mb-15{margin-bottom: 15px;}
            .mb-10{margin-bottom: 10px;}
            .mb-50{margin-bottom: 50px;}
            .mb-60{margin-bottom: 60px;}
            .table-bordered th, .table-bordered td {border: 1px solid #e5e7eb;}
            .table-sm th, .table-sm td{padding: 5px 10px;}
            .w-50{width: 50%;}
            .w-80{width: 80px;}
            .w-100{width: 100px;}
            .w-120{width: 120px;}
            .w-130{width: 130px;}
            .w-140{width: 140px;}
            .h-15{height: 15px;}
            .h-20{height: 20px;}
            .inline-block{display: inline-block;}
            .block{display: block;}


            .color-white{ color: #FFF;}
            .bg-darkish{background-color: #4A4A4A;}
            .color-darkish{color: #4A4A4A;}
            .bg-gryish{background-color: #d9d9d9;}
            .color-gryish{color: #d9d9d9;}
            .bg-darkish2{ background-color: #545454;}
            .color-darkish2{ color: #545454;}

            .quoteTitle{font-size: 48px;line-height: 1;letter-spacing: 5px;}
            .quoteDetails{line-height: 1.3; }

            .quoteItemsTable{border: none; line-height: 1.2;}
            .quoteItemsTable tr td, .quoteItemsTable tr th{padding: 17px 0 17px 30px; border: none;}
            .quoteItemsTable tr td:last-child, .quoteItemsTable tr th:last-child{padding-right: 30px; text-align: right;}
            .quoteItemsTable tr:nth-child(odd){background: #ede9e9;}

            .calculationTable tr td{ padding: 3px 0; }
            .wrapper{ position: relative; height: 100%; }
            .wrapper::after{position: absolute; right: 0; top: 0; width: 50%; height: 100%; content: ''; background: #d9d9d9; z-index: -1;}
        </style>
    </head>
    @php 
        $quoteItems = isset($quote->available_options->quoteItems) && !empty($quote->available_options->quoteItems) ? $quote->available_options->quoteItems : [];
        $quoteDiscounts = isset($quote->available_options->quoteDiscounts) && !empty($quote->available_options->quoteDiscounts) ? $quote->available_options->quoteDiscounts : [];
        $quoteNotes = isset($quote->available_options->quoteNotes) && !empty($quote->available_options->quoteNotes) ? $quote->available_options->quoteNotes : '';
        $quoteExtra = isset($quote->available_options->quoteExtra) && !empty($quote->available_options->quoteExtra) ? $quote->available_options->quoteExtra : [];
    @endphp
    <body>
        <div class="wrapper">
            <header></header>
            <table style="position: relative;">
                <tr>
                    <td class="w-50 v-top" style="padding: 60px 0 0 30px;">
                        @if(!empty($companyLogoBase64))
                            <img src="{{ $companyLogoBase64 }}" alt="Gas Safe Engineer APP" style="height: 50px; width: auto;">
                        @endif
                        <div class="customerDetails mb-8" style="margin-top: 95px;">
                            BILLED TO :<br/>
                            @if(isset($quote->customer->company_name) && !empty($quote->customer->company_name))
                            <span class="font-bold block" style="font-size: 16px; line-height: 1.1;">{{ $quote->customer->company_name }}</span>
                            @endif
                            <span class="font-bold block" style="font-size: 1r4px; line-height: 1.1;">{{ $quote->customer->full_name }}</span>
                            <span class="block" style="line-height: 1.1;">
                                @if(isset($quote->billing->full_address) && !empty($quote->billing->full_address))
                                    {!! (isset($quote->billing->full_address) ? $quote->billing->full_address : '') !!}
                                @elseif(isset($quote->job->billing->full_address) && !empty($quote->job->billing->full_address))
                                    {!! $quote->job->billing->full_address !!}
                                @else
                                    {!! (isset($quote->customer->full_address) ? $quote->customer->full_address : '') !!}
                                @endif
                            </span>
                        </div>
                        @if($quote->customer_property_id > 0)
                        <div class="jobDetails">
                            JOB ADDRESS: 
                            <span class="block" style="line-height: 1.1;">{!! (isset($quote->property->full_address) ? $quote->property->full_address : '') !!}</span>
                        </div>
                        @endif
                    </td>
                    <td class="w-50 text-right v-top" style="padding: 30px 30px 0 0;">
                        <img src="{{ $logoBase64 }}" alt="Gas Safe Engineer APP" style="width: 80px; height: auto;">
                        <div class="quoteTitle font-normal uppercase" style="margin-top: 45px;">Quote</div>
                        <div class="quoteDetails" style="margin: 70px 0 40px;">
                            <span class="block">Quote No. {{ $quote->quote_number }}</span>
                            @if(!empty($quote->issued_date) && !empty($quote->issued_date))
                                <span class="block">{{ date('F d, Y', strtotime($quote->issued_date)) }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
            <table class="quoteItemsTable" style="position: relative;">
                <tr>
                    <th class="uppercase font-normal w-50 text-left">Item description</th>
                    <th class="uppercase text-center">QTY</th>
                    <th class="uppercase text-center">price</th>
                    @if(isset($quoteExtra->non_vat_quote) && $quoteExtra->non_vat_quote != 1):
                    <th class="uppercase text-center">vat</th>
                    @endif
                    <th class="uppercase font-normal text-right">Total</th>
                </tr>
                @php 
                    $SUBTOTAL = 0;
                    $VATTOTAL = 0;
                    $TOTAL = 0;
                    $DUE = 0;
                    $DISCOUNTTOTAL = 0;
                    $DISCOUNTVATTOTAL = 0;
                @endphp
                @if(!empty($quoteItems))
                    @foreach($quoteItems as $item)
                        @php 
                            $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                            $unitPrice = (!empty($item->price) && $item->price > 0 ? $item->price : 0);
                            $vatRate = (!empty($item->vat) && $item->vat > 0 ? $item->vat : 0);
                            $vatAmount = ($unitPrice * $vatRate) / 100;
                            $lineTotal = (isset($quoteExtra->non_vat_quote) && $quoteExtra->non_vat_quote != 1 ? ($unitPrice * $units) + $vatAmount : ($unitPrice * $units));
                            
                            $SUBTOTAL += ($unitPrice * $units);
                            $VATTOTAL += $vatAmount;
                        @endphp

                        <tr>
                            <td class="font-normal w-50 text-left">
                                {!! (isset($item->description) && !empty($item->description) ? $item->description : 'Quote Item') !!}
                            </td>
                            <td class="text-center">
                                {{ ($units < 10 ? '0' : '').$units }}
                            </td>
                            <td class="text-center">
                                {{ Number::currency($unitPrice, 'GBP') }}
                            </td>
                            @if(isset($quoteExtra->non_vat_quote) && $quoteExtra->non_vat_quote != 1)
                                <td class="text-center">
                                    {{ $vatRate }}%
                                </td>
                            @endif
                            <td class="text-right">
                                {{ Number::currency($lineTotal, 'GBP') }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
            <table style="position: relative;">
                <tr>
                    <td class="w-50 v-top" style="padding: 0 0 0 30px;">
                        <div class="paymentInfo" style="font-size: 14px; line-height: 1.1; margin-top: 35px;">
                            <span class="block font-bold" style="margin-bottom: 6px;">Payment Information:</span>
                            @if(isset($quote->user->companies[0]->bank->name_on_account) && !empty($quote->user->companies[0]->bank->name_on_account))
                                <span class="block">{{ $quote->user->companies[0]->bank->name_on_account }}</span>
                            @endif
                            @if(isset($quote->user->companies[0]->bank->sort_code) && !empty($quote->user->companies[0]->bank->sort_code))
                                <span class="block">Sort code: {{ $quote->user->companies[0]->bank->sort_code }}</span>
                            @endif
                            @if(isset($quote->user->companies[0]->bank->account_number) && !empty($quote->user->companies[0]->bank->account_number))
                                <span class="block">Account no: {{ $quote->user->companies[0]->bank->account_number }}</span>
                            @endif
                        </div>
                        @if(isset($invoice->user->companies[0]->bank->payment_term) && !empty($invoice->user->companies[0]->bank->payment_term))
                        <div class="paymentInfo" style="font-size: 14px; line-height: 1.1; margin-top: 25px;">
                            <span class="block font-bold" style="margin-bottom: 6px;">Terms:</span>
                            <span class="block">{{ $invoice->user->companies[0]->bank->payment_term }}</span>
                        </div>
                        @endif
                        {{--@if(isset($quoteNotes) && !empty($quoteNotes))
                        <div class="paymentInfo" style="font-size: 14px; line-height: 1.1; margin-top: 25px;">
                            <span class="block font-bold" style="margin-bottom: 6px;">Note:</span>
                            <span class="block">{{ (isset($quoteNotes) && !empty($quoteNotes) ? $quoteNotes : '') }}</span>
                        </div>
                        @endif--}}
                    </td>
                    <td class="w-50 bg-gryish v-top" style="padding-top: 15px;">
                        @php 
                            $DISCOUNTTITLE = isset($quoteDiscounts->inv_item_title) ? $quoteDiscounts->inv_item_title : 'Discount';
                            $DISCOUNTUNITPRICE = (isset($quoteDiscounts->amount) ? $quoteDiscounts->amount : 0);

                            $DISCOUNTTOTAL += $DISCOUNTUNITPRICE;

                            $TOTAL = (isset($quoteExtra->non_vat_quote) && $quoteExtra->non_vat_quote != 1 ? $SUBTOTAL + $VATTOTAL : $SUBTOTAL) - $DISCOUNTTOTAL;
                            $DUE = $TOTAL;
                        @endphp
                        <table class="bg-darkish2 uppercase color-white calculationTable" style="padding: 12px 30px 12px 40px; font-size: 14px; line-height: 1;">
                            <tr>
                                <td class="text-left">Subtotal {{ isset($quoteExtra->non_vat_quote) && $quoteExtra->non_vat_quote != 1 ? ' (excl. VAT)' : '' }}</td>
                                <td class="text-right">{{ Number::currency($SUBTOTAL, 'GBP') }}</td>
                            </tr>
                            @if(isset($quoteExtra->non_vat_quote) && $quoteExtra->non_vat_quote != 1)
                            <tr>
                                <td class="text-left">Vat</td>
                                <td class="text-right">{{ Number::currency($VATTOTAL, 'GBP') }}</td>
                            </tr>
                            @endif
                            @if(!empty($quoteDiscounts))
                                <tr>
                                    <td class="text-left">{{ $DISCOUNTTITLE }}</td>
                                    <td class="text-right">-{{ Number::currency($DISCOUNTTOTAL, 'GBP') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-left">Total</td>
                                <td class="text-right">{{ Number::currency($TOTAL, 'GBP') }}</td>
                            </tr>
                        </table>
                        {{--<table class="bg-darkish2 uppercase color-white calculationTable" style="margin-top: 25px; padding: 12px 30px 12px 40px; font-size: 14px; line-height: 1;">
                            <tr>
                                <td class="text-left">Due</td>
                                <td class="text-right">{{ Number::currency($DUE, 'GBP') }}</td>
                            </tr>
                        </table>--}}
                    </td>
                </tr>
            </table>

            <footer>
                <p>
                    {{ isset($quote->user->companies[0]->company_name) && !empty($quote->user->companies[0]->company_name) ? $quote->user->companies[0]->company_name : '' }}
                     | 
                    {!! (isset($quote->user->companies[0]->full_address) ? $quote->user->companies[0]->full_address : '') !!}
                    {{ (isset($quote->user->companies[0]->company_phone) && !empty($quote->user->companies[0]->company_phone) ? ' | '.$quote->user->companies[0]->company_phone : '') }}
                </p>
                <div class="h-20 bg-darkish"></div>
            </footer>
        </div>
    </body>
</html>