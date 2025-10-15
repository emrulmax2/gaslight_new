<html>
    <head>
        <title>{{ $report_title }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
                        body{font-family: Tahoma, sans-serif; font-size: 14px; line-height: 20px; color: #475569; padding-top: 0;}
                        table{margin-left: 0px; width: 100%; border-collapse: collapse;}
                        figure{margin: 0;}

                        .text-center{text-align: center;}
                        .text-left{text-align: left;}
                        .text-right{text-align: right;}
                        @media print{ .pageBreak{page-break-after: always;} }
                        .pageBreak{page-break-after: always;}
                        .font-medium{font-weight: bold; }
                        .font-bold{font-weight: bold;}
                        .v-top{vertical-align: top;}
                        .text-primary{color: #164e63;}
                        .font-sm{font-size: 12px;}
                        .text-slate-400{color: #94a3b8;}
                        
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
                        .w-1/6{width: 16.666666%;}
                        .w-2/6{width: 33.333333%;}
                        .w-80{width: 80px;}
                        .w-100{width: 100px;}
                        .w-120{width: 120px;}
                        .w-130{width: 130px;}
                        .w-140{width: 140px;}
                        .inline-block{display: inline-block;}

                        .titleLabel{font-size: 20px; font-weight: bold; line-height: 28px; margin-bottom: 5px;}
                        .customerDetails{ position: relative;}
                        .customerName{font-size: 14px; line-height: 20px; margin-bottom: 4px; color: #64748b;}
                        .quoteTitle{font-size: 28px; line-height: 34px; margin-bottom: 0px;}
                        .quoteRef{font-size: 20px; line-height: 26px; margin-bottom: 8px;}
                        .calculationTable{width: 210px; float: right;}
                        .calculationTable tr th:first-child{text-align: right;}
                        .calculationTable tr th:last-child{width: 100px; text-align: right;}
                        .calculationTable tr th{padding-bottom: 8px;}
                        .calculationTable tr.totalRow th{border-bottom: 1px solid #e5e7eb;}
                        .calculationTable tr.advanceRow th{padding-top: 6px; border-bottom: 1px solid #e5e7eb;}
                    </style>
    </head>

    <body>

        <table class="mb-60">
            <tr>
                <td class="customerAddressWrap v-top">
                    <img src="{{ $logoBase64 }}" alt="Gas Safe Engineer APP" style="width: 126px; height: auto; margin-bottom: 20px;">
                    <div class="titleLabel">Address to</div>
                    <div class="customerDetails">
                        <div class="customerName font-medium">{{ $record->customer->full_name }}</div>
                        <div class="customerAddress">{!! (isset($record->customer->full_address_html) ? $record->customer->full_address_html : '') !!}</div>
                    </div>
                </td>
                <td class="quoteDetails text-right v-top">
                    <div class="quoteTitle font-bold">Quote</div>
                    <div class="quoteRef font-bold text-primary">Ref: {{ $record->certificate_number }}</div>
                    <div class="titleLabel mb-8">{{ $record->user->company->company_name }}</div>
                    <div class="companyAddress">{!! (isset($record->user->company->full_address_html) ? $record->user->company->full_address_html : '') !!}</div>
                    @if(isset($record->user->company->company_email) && !empty($record->user->company->company_email))
                        <div>{{ $record->user->company->company_email }}</div>
                    @endif
                    @if(isset($record->user->company->company_phone) && !empty($record->user->company->company_phone))
                        <div>{{ $record->user->company->company_phone }}</div>
                    @endif
                    @if($record->available_options->quoteExtra->non_vat_quote != 1)
                        <div class="vatNumberField pt-10 mb-1">
                            <span class="font-bold mr-3">VAT:</span>
                            <span>{{ $record->available_options->quoteExtra->vat_number }}</span>
                        </div>
                    @endif
                    @if(!empty($record->available_options->quoteExtra->issued_date) && !empty($record->available_options->quoteExtra->issued_date))
                        <div class="mb-1">
                            <span class="font-bold mr-3">Date:</span>
                            <span>{{ date('jS F, Y', strtotime($record->available_options->quoteExtra->issued_date)) }}</span>
                        </div>
                    @endif
                    @if(!empty($record->job->reference_no) && !empty($record->job->reference_no))
                        <div class="mb-1">
                            <span class="font-bold mr-3">Job Ref No:</span>
                            <span>{{ (isset($record->job->reference_no) ? $record->job->reference_no : '') }}</span>
                        </div>
                    @endif
                    <div class="titleLabel pt-10">Job Address</div>
                    <div class="companyAddress">{!! (isset($record->job->property->full_address_html) ? $record->job->property->full_address_html : '') !!}</div>
                </td>
            </tr>
        </table>

        <table class="table table-sm table-bordered quoteItemsTable mb-50">
            <thead>
                <tr>
                    <th class="text-left">DESCRIPTION</th>
                    <th class="text-right">UNITS</th>
                    <th class="text-right">PRICE</th>
                    @if($record->available_options->quoteExtra->non_vat_quote != 1)
                        <th class="text-right">VAT %</th>
                    @endif
                    <th class="text-right">TOTAL</th>
                </tr>
            </thead>

            @php
                $SUBTOTAL = 0;
                $VATTOTAL = 0;
                $TOTAL = 0;
                $DUE = 0;
                $DISCOUNTTOTAL = 0;
                $DISCOUNTVATTOTAL = 0;
                $ADVANCEAMOUNT = 0;
            @endphp
            @if(isset($record->available_options->quoteItems) && !empty($record->available_options->quoteItems))
                @foreach($record->available_options->quoteItems as $item)
                    @php 
                        $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                        $unitPrice = (!empty($item->price) && $item->price > 0 ? $item->price : 0);
                        $vatRate = (!empty($item->vat) && $item->vat > 0 ? $item->vat : 0);
                        $vatAmount = ($unitPrice * $vatRate) / 100;
                        $lineTotal = ($record->available_options->quoteExtra->non_vat_quote != 1 ? ($unitPrice * $units) + $vatAmount : ($unitPrice * $units));
                        
                        $SUBTOTAL += ($unitPrice * $units);
                        $VATTOTAL += $vatAmount;
                    @endphp

                    <tr>
                        <td>
                            {{ (isset($item->description) && !empty($item->description) ? $item->description : 'Quote Item') }}
                        </td>
                        <td class="w-80 text-right">
                            {{ $units }}
                        </td>
                        <td class="w-80 text-right font-medium">
                            {{ Number::currency($unitPrice, 'GBP') }}
                        </td>
                        @if($record->available_options->quoteExtra->non_vat_quote != 1)
                            <td class="w-80 text-right font-medium">
                                {{ $vatRate.'%' }}
                            </td>
                        @endif
                        <td class="w-80 text-right font-medium">
                            {{ Number::currency($lineTotal, 'GBP') }}
                        </td>
                    </tr>
        
                @endforeach
            @endif
            @if(isset($record->available_options->quoteDiscounts) && !empty($record->available_options->quoteDiscounts))
                @php    
                    $description = isset($record->available_options->quoteDiscounts->inv_item_title) ? $record->available_options->quoteDiscounts->inv_item_title : 'Discount';
                    $discountVatRate = (isset($record->available_options->quoteDiscounts->vat) ? $record->available_options->quoteDiscounts->vat : 0);
                    $discountUnitPrice = (isset($record->available_options->quoteDiscounts->amount) ? $record->available_options->quoteDiscounts->amount : 0);
                    $discountVatAmount = ($discountUnitPrice * $discountVatRate) / 100;

                    $DISCOUNTTOTAL += $discountUnitPrice;
                    $DISCOUNTVATTOTAL += $discountVatAmount;
                @endphp
                <tr>
                    <td>
                        {{ (!empty($description) ? $description : 'Quote Item') }}
                    </td>
                    <td class="w-80 text-right">
                        {{ 1 }}
                    </td>
                    <td class="w-80 text-right font-medium">
                        {{ Number::currency($discountUnitPrice, 'GBP') }}
                    </td>
                    @if($record->available_options->quoteExtra->non_vat_quote != 1)
                        <td class="w-80 text-right font-medium">
                            {{ $discountVatRate.'%' }}
                        </td>
                    @endif
                    <td class="w-80 text-right font-medium">
                        {{ '-'.Number::currency($discountUnitPrice, 'GBP') }}
                    </td>
                </tr>
            @endif
            @php
                $SUBTOTAL = $SUBTOTAL - $DISCOUNTTOTAL;
                $VATTOTAL = $VATTOTAL - $DISCOUNTVATTOTAL;
                $TOTAL = ($record->available_options->quoteExtra->non_vat_quote != 1 ? $SUBTOTAL + $VATTOTAL : $SUBTOTAL);
                $DUE = $TOTAL - $ADVANCEAMOUNT;
            @endphp
        </table>

        <table class="pdfSummaryTable">
            <tr>
                <td>&nbsp;</td>
                <td class="calculationColumns v-top">
                    <table class="calculationTable">
                        <tr>
                            <th>Subtotal:</th>
                            <th>{{ Number::currency($SUBTOTAL, 'GBP') }}</th>
                        </tr>
                        @if($record->non_vat_quote != 1)
                            <tr>
                                <th>VAT Total:</th>
                                <th>{{ Number::currency($VATTOTAL, 'GBP') }}</th>
                            </tr>
                        @endif
                        <tr class="totalRow">
                            <th>Total:</th>
                            <th>{{ Number::currency($TOTAL, 'GBP') }}</th>
                        </tr>
                        @if($ADVANCEAMOUNT > 0)
                            <tr class="advanceRow">
                                <th>Paid to Date:</th>
                                <th>{{ Number::currency($ADVANCEAMOUNT, 'GBP') }}</th>
                            </tr>
                        @endif
                        <tr>
                            <th>Due:</th>
                            <th>{{ Number::currency($DUE, 'GBP') }}</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div style="clear: both; width: 100%; height: 1px; margin-bottom: 40px;"></div>
        <table class="pdfSummaryTable">
            <tr>
                <td>
                    <table class="quoteInfoTable">
                        <tr>
                            <td class="v-top" style="width: 250px;">
                                @if((isset($record->user->company->bank->bank_name) && !empty($record->user->company->bank->bank_name)) || 
                                    (isset($record->user->company->bank->name_on_account) && !empty($record->user->company->bank->name_on_account)) || 
                                    (isset($record->user->company->bank->sort_code) && !empty($record->user->company->bank->sort_code)) || 
                                    (isset($record->user->company->bank->account_number) && !empty($record->user->company->bank->account_number)))
                                    <div class="font-medium mb-5">Bank Details</div>
                                    @if(isset($record->user->company->bank->bank_name) && !empty($record->user->company->bank->bank_name))
                                        <div class="mb-1">
                                            <span class="font-medium text-slate-400 inline-block w-140">Bank Name:</span>
                                            <span class="inline-block">{{ $record->user->company->bank->bank_name }}</span>
                                        </div>
                                    @endif
                                    @if(isset($record->user->company->bank->name_on_account) && !empty($record->user->company->bank->name_on_account))
                                        <div class="mb-1">
                                            <span class="font-medium text-slate-400 inline-block w-140">Account Name:</span>
                                            <span class="inline-block">{{ $record->user->company->bank->name_on_account }}</span>
                                        </div>
                                    @endif
                                    @if(isset($record->user->company->bank->sort_code) && !empty($record->user->company->bank->sort_code))
                                        <div class="mb-1">
                                            <span class="font-medium text-slate-400 inline-block w-140">Sort Code:</span>
                                            <span class="inline-block">{{ $record->user->company->bank->sort_code }}</span>
                                        </div>
                                    @endif
                                    @if(isset($record->user->company->bank->account_number) && !empty($record->user->company->bank->account_number))
                                        <div class="mb-1">
                                            <span class="font-medium text-slate-400 inline-block w-140">Account Number:</span>
                                            <span class="inline-block">{{ $record->user->company->bank->account_number }}</span>
                                        </div>
                                    @endif
                                @endif

                                @if(isset($record->available_options->quoteExtra->payment_term) && !empty($record->available_options->quoteExtra->payment_term))
                                    <div class="font-medium mb-4 pt-9">Payment Terms</div>
                                    <div class="mb-10">{{ (isset($record->available_options->quoteExtra->payment_term) && !empty($record->available_options->quoteExtra->payment_term) ? $record->available_options->quoteExtra->payment_term : '') }}</div>
                                @endif
                                @if(isset($record->available_options->quoteNotes) && !empty($record->available_options->quoteNotes))
                                    <div class="font-medium mb-4">Notes</div>
                                    <div>{!! $record->available_options->quoteNotes !!}</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>

    </body>
</html>