<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomerJob;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RecordInvoiceController extends Controller
{
   
    public function generatePdf(Request $request) {
        
        $invoiceItems = $request->input('invoiceItems');
        $isNonVatCheck = $request->input('isNonVatCheck', false);
        $date_issued = isset($request->date_issued) ? $request->date_issued : null;
        $jobRefNo = isset($request->jobRefNo) ? $request->jobRefNo : null;
        $customerJob = CustomerJob::find($request->customer_job_id);
        $company = Company::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get()->first();
        
    
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
                            <span>' . $customerJob->customer['full_name'] . '</span>
                            <div style="margin-top: 0.25rem; line-height: 1.5; font-size: 14px;">
                                <span>' . $customerJob->customer['address_line_1'] . '</span>
                                <span>' . $customerJob->customer['address_line_2'] . '</span>,<br>
                                <span>' . $customerJob->customer['city'] . '</span>
                                <span>' . $customerJob->customer['postal_code'] . '</span>,<br>
                                <span>' . $customerJob->customer['state'] . '</span>
                            </div>
                        </div>
                        <div style="width: 50%; text-align: right; float: right;">
                            <div style="font-size: 1.875rem; font-weight: 600; color: #164e63; text-align: right;">Invoice</div>
                            <div style="margin-top: 0.5rem; font-weight: 700; font-size: 1.25rem; color: #475569;">' . $company->company_name . '</div>
                            <div style="margin-top: 0.25rem; line-height: 1.5; font-size: 14px;">
                                <span>' . $company->company_address_line_1 . '</span>
                                <span>' . $company->company_address_line_2 . '</span>,<br>
                                <span>' . $company->company_city . '</span>,
                                <span>' . $company->company_postal_code . '</span>,<br>
                                <span>' . $company->company_state . '</span><br>
                                <span>' . $company->company_country . '</span><br>
                                <span><strong>VAT:</strong> ' . $company->vat_number . '</span>
                            </div>
                            <div style="margin-top: 0.5rem;"><strong>Date Issued: </strong>' . $date_issued . '</div>
                            <div style="margin-top: 0.5rem;"><strong>Job Ref No: </strong>' . $jobRefNo . '</div>

                            <div style="margin-top: 1rem; font-weight: 700; font-size: 1.25rem;color: #475569;">Job Address</div>
                            <div style="margin-top: 0.25rem; line-height: 1.5; font-size: 14px;">
                                <span>' . $customerJob->property['address_line_1'] . '</span>
                                <span>' . $customerJob->property['address_line_2'] . '</span>,<br>
                                <span>' . $customerJob->property['city'] . '</span>,
                                <span>' . $customerJob->property['postal_code'] . '</span><br>
                                <span>' . $customerJob->property['state'] . '</span><br>
                                <span>' . $customerJob->property['country'] . '</span><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                        <thead>
                            <tr style="background-color: #f3f4f6;">
                                <th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: left;">DESCRIPTION</th>
                                <th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: right;">Units</th>
                                <th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: right;">PRICE</th>
                                ' . (!$isNonVatCheck ? '<th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: right;">VAT</th>' : '') . '
                                <th style="font-weight: 600; padding: 0.75rem; border-bottom: 2px solid #e5e7eb; text-align: right;">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>';
    
                        $subtotal = 0;
                        $vatTotal = 0;
                        foreach ($invoiceItems as $item) {
                            $lineTotal = $item['units'] * $item['price'];
                            $vatAmount = !$isNonVatCheck ? ($lineTotal * ($item['vat'] / 100)) : 0;
                            $subtotal += $lineTotal;
                            $vatTotal += $vatAmount;
                    
                            $html .= '
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 0.75rem;">' . $item['description'] . '</td>
                                <td style="padding: 0.75rem; text-align: right;">' . $item['units'] . '</td>
                                <td style="padding: 0.75rem; text-align: right;">£' . number_format($item['price'], 2) . '</td>
                            ' . (!$isNonVatCheck ? '<td style="padding: 0.75rem; text-align: right;">' .  $item['vat'].'%' . '</td>' : '') . '
                                <td style="padding: 0.75rem; text-align: right;">£' . number_format($lineTotal, 2) . '</td>
                            </tr>';
                        }
                    
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
    
        $fileName = 'invoice_' . time() . '.pdf';
        $pdfPath = public_path('invoices/' . $fileName);
        Pdf::loadHTML($PDFHTML)->setWarnings(false)->save($pdfPath);
    
        return response()->json(['pdf_path' => asset('invoices/' . $fileName)]);
    }
    
    public function approveAndSendEmail(Request $request) {
        

        $pdfResponse = $this->generatePdf($request);
    
        $pdfPath = $pdfResponse->getData()->pdf_path;
    
        return response()->json(['message' => 'PDF generated and email sent successfully', 'pdf_path' => $pdfPath]);
    }
}
