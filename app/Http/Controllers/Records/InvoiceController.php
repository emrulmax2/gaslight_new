<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomerJob;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function store(Request $request){
        $submit_type = (isset($request->submit_type) && !empty($request->submit_type) ? $request->submit_type : 1);
        $customer_job_id = $request->customer_job_id;
        $customer_id = $request->customer_id;
        $job_form_id = $request->job_form_id;
        $invoice_id = $request->invoice_id;
        $user_id = auth()->user()->id;

        $data = [
            'issued_date' => (isset($request->issued_date) && !empty($request->issued_date) ? date('Y-m-d', strtotime($request->issued_date)) : null),
            'reference_no' => (isset($request->reference_no) && !empty($request->reference_no) ? $request->reference_no : null),
            'non_vat_invoice' => (isset($request->non_vat_invoice) && $request->non_vat_invoice > 0 ? $request->non_vat_invoice : 0),
            'advance_amount' => (isset($request->inv_advance_amount) && !empty($request->inv_advance_amount) ? $request->inv_advance_amount : null),
            'payment_method_id' => (isset($request->inv_payment_method_id) && !empty($request->inv_payment_method_id) ? $request->inv_payment_method_id : null),
            'advance_date' => (isset($request->inv_advance_date) && !empty($request->inv_advance_date) ? date('Y-m-d', strtotime($request->inv_advance_date)) : null),
            'notes' => (!empty($request->notes) ? $request->notes : null),
            'payment_term' => (!empty($request->payment_term) ? $request->payment_term : null),
            
            'updated_by' => $user_id
        ];
        if($submit_type != 1):
            $data['status'] = 'Approved';
        endif;
        $invoice = Invoice::where('id', $invoice_id)->update($data);

        /* Update Invoice Items */
        InvoiceItem::where('invoice_id', $invoice_id)->forceDelete();
        $inv = (isset($request->inv) && !empty($request->inv) ? $request->inv : []);
        if(!empty($inv)):
            $discount = (isset($inv['discount']) && !empty($inv['discount']) ? $inv['discount'] : []);
            foreach($inv as $type => $item):
                if($type != 'discount'):
                    $units = (isset($item['units']) && $item['units'] > 0 ? $item['units'] : 0);
                    $unit_price = (isset($item['unit_price']) && $item['unit_price'] > 0 ? $item['unit_price'] : 0);
                    $vat_rate = (isset($item['vat_rate']) && $item['vat_rate'] > 0 ? $item['vat_rate'] : 0);
                    $vat_amount = (isset($item['vat_amount']) && $item['vat_amount'] > 0 ? $item['vat_amount'] : 0);
                    InvoiceItem::create([
                        'invoice_id' => $invoice_id,
                        'type' => 'Default',
                        'description' => (isset($item['descritpion']) && !empty($item['descritpion']) ? $item['descritpion'] : 'Invoice Item'),
                        'units' => $units,
                        'unit_price' => $unit_price,
                        'vat_rate' => $vat_rate,
                        'vat_amount' => $vat_amount,
                        
                        'created_by' => $user_id,
                        'updated_by' => $user_id,
                    ]);
                endif;
            endforeach;
            if(!empty($discount)):
                $units = (isset($discount['units']) && $discount['units'] > 0 ? $discount['units'] : 0);
                $unit_price = (isset($discount['unit_price']) && $discount['unit_price'] > 0 ? $discount['unit_price'] : 0);
                $vat_rate = (isset($discount['vat_rate']) && $discount['vat_rate'] > 0 ? $discount['vat_rate'] : 0);
                $vat_amount = (isset($discount['vat_amount']) && $discount['vat_amount'] > 0 ? $discount['vat_amount'] : 0);
                InvoiceItem::create([
                    'invoice_id' => $invoice_id,
                    'type' => 'Discount',
                    'description' => (isset($discount['description']) && !empty($discount['description']) ? $discount['description'] : 'Discount'),
                    'units' => $units,
                    'unit_price' => $unit_price,
                    'vat_rate' => $vat_rate,
                    'vat_amount' => $vat_amount,
                    
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]);
            endif;
        endif;
        
        $pdf = $this->generatePDF($invoice_id);
        $message = ($submit_type == 3 ? 'Invoice has been approved and email successfully sent to the customer.' : ($submit_type == 2 ? 'Invoice has been approved.' : 'Invoice successfully generaged'));
        return response()->json(['msg' => $message, 'red' => '', 'pdf' => $pdf], 200);
    }


    public function generatePdf($invoice_id) {
        $invoice = Invoice::with('items', 'job', 'job.property', 'customer', 'user', 'user.company')->find($invoice_id);
        $isNonVatCheck = ($invoice->vat_registerd == 1 ? true : false);
    
        $logoPath = resource_path('images/gas_safe_register.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $report_title = 'Invoice of '.$invoice->invoice_number;
        $PDFHTML = '';
        $PDFHTML .= '<html>';
            $PDFHTML .= '<head>';
                $PDFHTML .= '<title>'.$report_title.'</title>';
                $PDFHTML .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
                $PDFHTML .= '<style>
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
                                .invoiceTitle{font-size: 28px; line-height: 34px; margin-bottom: 0px;}
                                .invoiceRef{font-size: 20px; line-height: 26px; margin-bottom: 8px;}
                                .calculationTable{width: 210px; float: right;}
                                .calculationTable tr th:first-child{text-align: right;}
                                .calculationTable tr th:last-child{width: 100px; text-align: right;}
                                .calculationTable tr th{padding-bottom: 8px;}
                                .calculationTable tr.totalRow th{border-bottom: 1px solid #e5e7eb;}
                                .calculationTable tr.advanceRow th{padding-top: 6px; border-bottom: 1px solid #e5e7eb;}
                            </style>';
            $PDFHTML .= '</head>';

            $PDFHTML .= '<body>';

                $PDFHTML .= '<table class="mb-60">';
                    $PDFHTML .= '<tr>';
                        $PDFHTML .= '<td class="customerAddressWrap v-top">';
                            $PDFHTML .= '<img src="' . $logoBase64 . '" alt="Gas Safe Engineer APP" style="width: 126px; height: auto; margin-bottom: 20px;">';
                            $PDFHTML .= '<div class="titleLabel">Address to</div>';
                            $PDFHTML .= '<div class="customerDetails">';
                                $PDFHTML .= '<div class="customerName font-medium">'.$invoice->customer->full_name.'</div>';
                                $PDFHTML .= '<div class="customerAddress">'.(isset($invoice->customer->full_address_html) ? $invoice->customer->full_address_html : '').'</div>';
                            $PDFHTML .= '</div>';
                        $PDFHTML .= '</td>';
                        $PDFHTML .= '<td class="invoiceDetails text-right v-top">';
                            $PDFHTML .= '<div class="invoiceTitle font-bold">Invoice</div>';
                            $PDFHTML .= '<div class="invoiceRef font-bold text-primary">Ref: '.$invoice->invoice_number.'</div>';
                            $PDFHTML .= '<div class="titleLabel mb-8">'.$invoice->user->company->company_name.'</div>';
                            $PDFHTML .= '<div class="companyAddress">'.(isset($invoice->user->company->full_address_html) ? $invoice->user->company->full_address_html : '').'</div>';
                            if(isset($invoice->user->company->company_email) && !empty($invoice->user->company->company_email)):
                                $PDFHTML .= '<div>'.$invoice->user->company->company_email.'</div>';
                            endif;
                            if(isset($invoice->user->company->company_phone) && !empty($invoice->user->company->company_phone)):
                                $PDFHTML .= '<div>'.$invoice->user->company->company_phone.'</div>';
                            endif;
                            if($invoice->non_vat_invoice != 1):
                                $PDFHTML .= '<div class="vatNumberField pt-10 mb-1">';
                                    $PDFHTML .= '<span class="font-bold mr-3">VAT:</span>';
                                    $PDFHTML .= '<span>'.$invoice->vat_number.'</span>';
                                $PDFHTML .= '</div>';
                            endif;
                            if(!empty($invoice->issued_date) && !empty($invoice->issued_date)):
                                $PDFHTML .= '<div class="mb-1">';
                                    $PDFHTML .= '<span class="font-bold mr-3">Date:</span>';
                                    $PDFHTML .= '<span>'.date('jS F, Y', strtotime($invoice->issued_date)).'</span>';
                                $PDFHTML .= '</div>';
                            endif;
                            if(!empty($invoice->issued_date) && !empty($invoice->issued_date)):
                                $PDFHTML .= '<div class="mb-1">';
                                    $PDFHTML .= '<span class="font-bold mr-3">Job Ref No:</span>';
                                    $PDFHTML .= '<span>'.(isset($invoice->reference_no) ? $invoice->reference_no : '').'</span>';
                                $PDFHTML .= '</div>';
                            endif;
                            $PDFHTML .= '<div class="titleLabel pt-10">Job Address</div>';
                            $PDFHTML .= '<div class="companyAddress">'.(isset($invoice->user->company->full_address_html) ? $invoice->user->company->full_address_html : '').'</div>';
                        $PDFHTML .= '</td>';
                    $PDFHTML .= '</tr>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm table-bordered invoiceItemsTable mb-50">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="text-left">DESCRIPTION</th>';
                            $PDFHTML .= '<th class="text-right">UNITS</th>';
                            $PDFHTML .= '<th class="text-right">PRICE</th>';
                            if($invoice->non_vat_invoice != 1):
                                $PDFHTML .= '<th class="text-right">VAT %</th>';
                            endif;
                            $PDFHTML .= '<th class="text-right">TOTAL</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';

                    $SUBTOTAL = 0;
                    $VATTOTAL = 0;
                    $TOTAL = 0;
                    $DUE = 0;
                    $DISCOUNTTOTAL = 0;
                    $DISCOUNTVATTOTAL = 0;
                    if(isset($invoice->items) && $invoice->items->count() > 0):
                        foreach($invoice->items as $item):
                            $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                            $unitPrice = (!empty($item->unit_price) && $item->unit_price > 0 ? $item->unit_price : 0);
                            $vatRate = (!empty($item->vat_rate) && $item->vat_rate > 0 ? $item->vat_rate : 0);
                            $vatAmount = ($unitPrice * $vatRate) / 100;
                            $lineTotal = ($unitPrice * $units) + $vatAmount;
                            if($item->type == 'Discount'):

                            endif;

                            $PDFHTML .= '<tr>';
                                $PDFHTML .= '<td>';
                                    $PDFHTML .= (isset($item->description) && !empty($item->description) ? $item->description : 'Invoice Item');
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-80 text-right">';
                                    $PDFHTML .= $units;
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-80 text-right font-medium">';
                                    $PDFHTML .= Number::currency($unitPrice, 'GBP');
                                $PDFHTML .= '</td>';
                                if($invoice->non_vat_invoice != 1):
                                    $PDFHTML .= '<td class="w-80 text-right font-medium">';
                                        $PDFHTML .= $vatRate.'%';
                                    $PDFHTML .= '</td>';
                                endif;
                                $PDFHTML .= '<td class="w-80 text-right font-medium">';
                                    $PDFHTML .= ($item->type == 'Discount' ? '-' : '').Number::currency($lineTotal, 'GBP');
                                $PDFHTML .= '</td>';
                            $PDFHTML .= '</tr>';
                        endforeach;
                    endif;
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="pdfSummaryTable">';
                    $PDFHTML .= '<tr>';
                        $PDFHTML .= '<td>';
                            $PDFHTML .= '<table class="invoiceInfoTable">';
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<td class="v-top" style="width: 250px;">';
                                        $PDFHTML .= '<div class="font-medium mb-5">Bank Details</div>';
                                        if(isset($invoice->user->company->bank->bank_name) && !empty($invoice->user->company->bank->bank_name)):
                                            $PDFHTML .= '<div class="mb-1">';
                                                $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Bank Name:</span>';
                                                $PDFHTML .= '<span class="inline-block">'.$invoice->user->company->bank->bank_name.'</span>';
                                            $PDFHTML .= '</div>';
                                        endif;
                                        if(isset($invoice->user->company->bank->name_on_account) && !empty($invoice->user->company->bank->name_on_account)):
                                            $PDFHTML .= '<div class="mb-1">';
                                                $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Account Name:</span>';
                                                $PDFHTML .= '<span class="inline-block">'.$invoice->user->company->bank->name_on_account.'</span>';
                                            $PDFHTML .= '</div>';
                                        endif;
                                        if(isset($invoice->user->company->bank->sort_code) && !empty($invoice->user->company->bank->sort_code)):
                                            $PDFHTML .= '<div class="mb-1">';
                                                $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Sort Code:</span>';
                                                $PDFHTML .= '<span class="inline-block">'.$invoice->user->company->bank->sort_code.'</span>';
                                            $PDFHTML .= '</div>';
                                        endif;
                                        if(isset($invoice->user->company->bank->account_number) && !empty($invoice->user->company->bank->account_number)):
                                            $PDFHTML .= '<div class="mb-1">';
                                                $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Account Number:</span>';
                                                $PDFHTML .= '<span class="inline-block">'.$invoice->user->company->bank->account_number.'</span>';
                                            $PDFHTML .= '</div>';
                                        endif;

                                        $PDFHTML .= '<div class="font-medium mb-4 pt-9">Payment Terms</div>';
                                        $PDFHTML .= '<div class="mb-10">'.(isset($invoice->payment_term) && !empty($invoice->payment_term) ? $invoice->payment_term : '').'</div>';
                                        $PDFHTML .= '<div class="font-medium mb-4">Notes</div>';
                                        $PDFHTML .= '<div>'.(isset($invoice->notes) && !empty($invoice->notes) ? $invoice->notes : '').'</div>';
                                    $PDFHTML .= '</td>';
                                $PDFHTML .= '</tr>';
                            $PDFHTML .= '</table>';
                        $PDFHTML .= '</td>';
                        $PDFHTML .= '<td class="calculationColumns">';
                            $PDFHTML .= '<table class="calculationTable">';
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<th>Subtotal:</th>';
                                    $PDFHTML .= '<th>'.Number::currency(0, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<th>VAT Total:</th>';
                                    $PDFHTML .= '<th>'.Number::currency(0, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                                $PDFHTML .= '<tr class="totalRow">';
                                    $PDFHTML .= '<th>Total:</th>';
                                    $PDFHTML .= '<th>'.Number::currency(0, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                                $PDFHTML .= '<tr class="advanceRow">';
                                    $PDFHTML .= '<th>Paid to Date:</th>';
                                    $PDFHTML .= '<th>'.Number::currency(0, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<th>Due:</th>';
                                    $PDFHTML .= '<th>'.Number::currency(0, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                            $PDFHTML .= '</table>';
                        $PDFHTML .= '</td>';
                    $PDFHTML .= '</tr>';
                $PDFHTML .= '</table>';

            $PDFHTML .= '</body>';
        $PDFHTML .= '</html>';


        $fileName = $invoice->invoice_number.'.pdf';
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('invoices/'.$invoice->customer_job_id.'/'.$invoice->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('invoices/'.$invoice->customer_job_id.'/'.$invoice->job_form_id.'/'.$fileName);
        
    }
}
