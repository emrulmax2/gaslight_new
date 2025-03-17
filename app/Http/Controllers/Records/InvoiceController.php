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
    
        $html = '
        <div style="margin-top: 1rem;">
            <div style="background-color: #fff; border-radius: 0.375rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden; font-family: Arial, sans-serif;">
                <div style="border-bottom: 1px solid rgba(226, 232, 240, 0.6); padding-bottom: 1rem; overflow: hidden;">
                   <div style="width: 100%; display: flex; justify-content: space-between; align-items: flex-start; clear: both; height: 400px;">
                        <div style="width: 50%; text-align: left; float: left;">
                            <img src="' . $logoBase64 . '" alt="Gas Safe Register Logo" style="width: 7rem;">
                            <div style="margin-top: 0.5rem; font-weight: 700; font-size: 1.25rem;margin-bottom:1rem;color: #475569;">Address to</div>
                            <span>' . $invoice->customer->full_name.'</span>
                            <div style="margin-top: 0.25rem; line-height: 1.5; font-size: 14px;">
                                ' . (isset($invoice->customer->full_address_html) ? $invoice->customer->full_address_html : '').'
                            </div>
                        </div>
                        <div style="width: 50%; text-align: right; float: right;">
                            <div style="font-size: 1.875rem; font-weight: 600; text-align: right;">Invoice <span style="color: #164e63;">#'.$invoice->invoice_number.'</span></div>
                            <div style="margin-top: 0.5rem; font-weight: 700; font-size: 1.25rem; color: #475569;">' . $invoice->user->company->company_name . '</div>
                            <div style="margin-top: 0.25rem; line-height: 1.5; font-size: 14px;">
                                '.(isset($invoice->user->company->full_address_html) ? $invoice->user->company->full_address_html.'<br/>' : '').'
                                '.($invoice->vat_registerd == 1 && $invoice->vat_number != '' ? '<span><strong>VAT:</strong> '.$invoice->vat_number.'</span>' : '').'
                            </div>
                            <div style="margin-top: 0.5rem;"><strong>Date Issued: </strong>'.(!empty($invoice->issued_date) ? date('jS F, Y', strtotime($invoice->issued_date)) : '').'</div>
                            <div style="margin-top: 0.5rem;"><strong>Job Ref No: </strong>'.(!empty($invoice->reference_no) ? $invoice->reference_no : '').'</div>

                            <div style="margin-top: 1rem; font-weight: 700; font-size: 1.25rem;color: #475569;">Job Address</div>
                            <div style="margin-top: 0.25rem; line-height: 1.5; font-size: 14px;">
                                <span>'.(isset($invoice->job->property->full_address_html) ? $invoice->job->property->full_address_html : '').'</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                        <thead>
                            <tr style="background-color: #f3f4f6;">
                                <th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: left;">DESCRIPTION</th>
                                <th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: right; width: 120px;">Units</th>
                                <th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: right; width: 120px;">PRICE</th>
                                ' . (!$isNonVatCheck ? '<th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: right; width: 120px;">VAT</th>' : '') . '
                                <th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: right; width: 120px;">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>';
    
                        $subtotal = 0;
                        $vatTotal = 0;

                        if(isset($invoice->items) && $invoice->items->count() > 0):
                            foreach ($invoice->items as $item):
                                $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                                $unitPrice = (!empty($item->unit_price) && $item->unit_price > 0 ? $item->unit_price : 0);
                                $vatRate = (!empty($item->vat_rate) && $item->vat_rate > 0 ? $item->vat_rate : 0);
                                $vatAmount = ($unitPrice * $vatRate) / 100;
                                $lineTotal = ($unitPrice * $units) + $vatAmount;
                                $subtotal += $lineTotal;
                                $vatTotal += $vatAmount;
                        
                                $html .= '
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 0.75rem;">'.$item->description.'</td>
                                    <td style="padding: 0.75rem; text-align: right;">'.$units.'</td>
                                    <td style="padding: 0.75rem; text-align: right;">'.Number::currency($unitPrice, 'GBP').'</td>
                                ' . (!$isNonVatCheck ? '<td style="padding: 0.75rem; text-align: right;">'.$vatRate.'%'.'</td>' : '') . '
                                    <td style="padding: 0.75rem; text-align: right;">'.Number::currency($lineTotal, 'GBP').'</td>
                                </tr>';
                            endforeach;
                        endif;
                        $total = $subtotal + $vatTotal;

                        $html .= '
                        </tbody>
                    </table>
                </div>
                <div style="text-align: right; font-size: 14px;">
                    <div style="margin-bottom: 0.5rem;"><strong>Subtotal:</strong> £' . number_format($subtotal, 2) . '</div>
                    ' . (!$isNonVatCheck ? '<div style="margin-bottom: 0.5rem;"><strong>VAT Total:</strong> £' . number_format($vatTotal, 2) . '</div>' : '') . '
                    <div style="font-weight: 700; font-size: 16px; border-bottom: 1px solid #e5e7eb; margin-bottom: 0.5rem; padding-bottom: 0.5rem;"><strong>Total:</strong> £' . number_format($total, 2) . '</div>
                    <div><strong>Due:</strong> £' . number_format($total, 2) . '</div>
                </div>
            </div>
        </div>';
    
        $PDFHTML = '<html><head><title>Invoice</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>';
        $PDFHTML .= $html;
        $PDFHTML .= '</body></html>';

        $fileName = $invoice->invoice_number.'.pdf';
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true])
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('invoices/'.$invoice->customer_job_id.'/'.$invoice->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('invoices/'.$invoice->customer_job_id.'/'.$invoice->job_form_id.'/'.$fileName);
    }
}
