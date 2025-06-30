<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobForm;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormPrefixMumbering;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

class InvoiceController extends Controller
{
     public function checkAndUpdateRecordHistory($record_id){
        $record = Invoice::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => Invoice::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => Invoice::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]); 
    }

    public function getDetails($invoice_id){
        try {
            $invoice = Invoice::with(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'items'])->findOrFail($invoice_id);
            $user_id = Auth::user()->id;
            $form = JobForm::find($invoice->job_form_id);
            $record = $form->slug;

            if(empty($inv->invoice_number)):
                $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
                $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
                $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
                $userLastInvoice = Invoice::where('customer_job_id', $invoice->customer_job_id)->where('job_form_id', $form->id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
                $lastInvoiceNo = (isset($userLastInvoice->invoice_number) && !empty($userLastInvoice->invoice_number) ? $userLastInvoice->invoice_number : '');

                $invSerial = $starting_form;
                if(!empty($lastInvoiceNo)):
                    preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
                    $invSerial = (int) $invoiceNumbers[1] + 1;
                endif;
                $invoiceNumber = $prifix.str_pad($invSerial, 6, '0', STR_PAD_LEFT);
                Invoice::where('id', $invoice->id)->update(['invoice_number' => $invoiceNumber]);
            endif;

            $thePdf = $this->generatePdf($invoice->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'form' => $form,
                    'invoice' => $invoice,
                    'pdf_url' => $thePdf
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found. The requested Invoice (ID: '.$invoice_id.') does not exist or may have been deleted.',
            ], 404);
            
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found. . The requested Invoice (ID: '.$invoice_id.') does not exist or may have been deleted.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $job_form_id = 4;
            
            $user_id = request()->user()->id;
            $user = User::find($user_id);
            $company = (isset($user->companies[0]) && !empty($user->companies[0]) ? $user->companies[0] : []);
            $form = JobForm::find($job_form_id);

            $invoice_id = (isset($request->invoice_id) && $request->invoice_id > 0 ? $request->invoice_id : 0);
            $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
            $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
            $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
            $property = CustomerProperty::find($customer_property_id);
            
            $nonVatInvoice = (isset($request->non_vat_invoice) && $request->non_vat_invoice == 1 ? true : false);
            $invoiceItems = $request->invoiceItems;
            $invoiceDiscounts = $request->invoiceDiscounts;
            $invoiceAdvance = $request->invoiceAdvance;
            $invoiceNotes = $request->invoiceNotes;

            if($customer_job_id == 0) {
                $customerJob = CustomerJob::create([
                    'customer_id' => $customer_id,
                    'customer_property_id' => $customer_property_id,
                    'description' => $form->name,
                    'details' => 'Job created for '.$property->full_address,
                    'created_by' => request()->user()->id
                ]);
                $customer_job_id = ($customerJob->id ? $customerJob->id : $customer_job_id);
            }

            if($customer_job_id > 0) {
                $job = CustomerJob::find($customer_job_id);
                $invoice = Invoice::updateOrCreate(['id' => $invoice_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                    'customer_id' => $customer_id,
                    'customer_job_id' => $customer_job_id,
                    'job_form_id' => $job_form_id,
                    'invoice_number' => $request->invoice_number,
                    'issued_date' => (isset($request->issued_date) && !empty($request->issued_date) ? date('Y-m-d', strtotime($request->issued_date)) : date('Y-m-d')),
                    'reference_no' => (isset($job->reference_no) && !empty($job->reference_no) ? $job->reference_no : null),
                    'non_vat_invoice' => ($nonVatInvoice ? 1 : 0),
                    'vat_number' => (isset($request->vat_number) && !empty($request->vat_number) ? $request->vat_number : null),
                    'advance_amount' => (isset($invoiceAdvance->advance_amount) && !empty($invoiceAdvance->advance_amount) ? $invoiceAdvance->advance_amount : null),
                    'payment_method_id' => (isset($invoiceAdvance->payment_method_id) && !empty($invoiceAdvance->payment_method_id) ? $invoiceAdvance->payment_method_id : null),
                    'advance_date' => (isset($invoiceAdvance->advance_pay_date) && !empty($invoiceAdvance->advance_pay_date) ? date('Y-m-d', strtotime($invoiceAdvance->advance_pay_date)) : null),
                    'notes' => (!empty($invoiceNotes) ? $invoiceNotes : null),
                    'payment_term' => (isset($company->bank->payment_term) && !empty($company->bank->payment_term) ? $company->bank->payment_term : null),
                    'updated_by' => $user_id,
                ]);
                $this->checkAndUpdateRecordHistory($invoice->id);

                InvoiceItem::where('invoice_id', $invoice->id)->forceDelete();
                if(!empty($invoiceItems)) {
                    foreach($invoiceItems as $key => $item) {
                        $units = (isset($item->units) && $item->units > 0 ? $item->units : 0);
                        $unit_price = (isset($item->price) && $item->price > 0 ? $item->price : 0);
                        $vat_rate = (isset($item->vat) && $item->vat > 0 ? $item->vat : 0);
                        
                        $item_total = $unit_price * $units;
                        $vat_amount = ($item_total * $vat_rate) / 100;

                        InvoiceItem::create([
                            'invoice_id' => $invoice->id,
                            'type' => 'Default',
                            'description' => (isset($item->description) && !empty($item->description) ? $item->description : 'Invoice Item'),
                            'units' => $units,
                            'unit_price' => $unit_price,
                            'vat_rate' => $vat_rate,
                            'vat_amount' => $vat_amount,
                            'created_by' => $user_id,
                            'updated_by' => $user_id,
                        ]);
                    }
                }

                if(!empty($invoiceDiscounts)) {
                    $units = 1;
                    $unit_price = (isset($invoiceDiscounts->amount) && $invoiceDiscounts->amount > 0 ? $invoiceDiscounts->amount : 0);
                    $vat_rate = (isset($item->vat) && $item->vat > 0 ? $item->vat : 0);
                    
                    $vat_amount = ($unit_price * $vat_rate) / 100;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'type' => 'Discount',
                        'description' => 'Discount',
                        'units' => $units,
                        'unit_price' => $unit_price,
                        'vat_rate' => $vat_rate,
                        'vat_amount' => $vat_amount,
                        'created_by' => $user_id,
                        'updated_by' => $user_id,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Invoice successfully created.',
                    'data' => Invoice::with(['customer', 'job', 'form', 'items'])->findOrFail($invoice->id),
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later or contact with the administrator.'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator.',
            ], 500);
        }
    }


    public function generatePdf($invoice_id) {
        Log::info($invoice_id);
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
                            $PDFHTML .= '<div class="invoiceRef font-bold text-primary">'.$invoice->invoice_number.'</div>';
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
                            $PDFHTML .= '<div class="companyAddress">'.(isset($invoice->job->property->full_address_html) ? $invoice->job->property->full_address_html : '').'</div>';
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
                    $ADVANCEAMOUNT = (isset($invoice->advance_amount) && $invoice->advance_amount > 0 ? $invoice->advance_amount : 0);
                    if(isset($invoice->items) && $invoice->items->count() > 0):
                        foreach($invoice->items as $item):
                            $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                            $unitPrice = (!empty($item->unit_price) && $item->unit_price > 0 ? $item->unit_price : 0);
                            $vatRate = (!empty($item->vat_rate) && $item->vat_rate > 0 ? $item->vat_rate : 0);
                            $vatAmount = ($unitPrice * $vatRate) / 100;
                            $lineTotal = ($invoice->non_vat_invoice != 1 ? ($unitPrice * $units) + $vatAmount : ($unitPrice * $units));
                            if($item->type == 'Discount'):
                                $DISCOUNTTOTAL += ($unitPrice * $units);
                                $DISCOUNTVATTOTAL += $vatAmount;
                            else:
                                $SUBTOTAL += ($unitPrice * $units);
                                $VATTOTAL += $vatAmount;
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
                    $SUBTOTAL = $SUBTOTAL - $DISCOUNTTOTAL;
                    $VATTOTAL = $VATTOTAL - $DISCOUNTVATTOTAL;
                    $TOTAL = ($invoice->non_vat_invoice != 1 ? $SUBTOTAL + $VATTOTAL : $SUBTOTAL);
                    $DUE = $TOTAL - $ADVANCEAMOUNT;
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="pdfSummaryTable">';
                    $PDFHTML .= '<tr>';
                        $PDFHTML .= '<td>&nbsp;</td>';
                        $PDFHTML .= '<td class="calculationColumns">';
                            $PDFHTML .= '<table class="calculationTable">';
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<th>Subtotal:</th>';
                                    $PDFHTML .= '<th>'.Number::currency($SUBTOTAL, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                                if($invoice->non_vat_invoice != 1):
                                    $PDFHTML .= '<tr>';
                                        $PDFHTML .= '<th>VAT Total:</th>';
                                        $PDFHTML .= '<th>'.Number::currency($VATTOTAL, 'GBP').'</th>';
                                    $PDFHTML .= '</tr>';
                                endif;
                                $PDFHTML .= '<tr class="totalRow">';
                                    $PDFHTML .= '<th>Total:</th>';
                                    $PDFHTML .= '<th>'.Number::currency($TOTAL, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                                if($ADVANCEAMOUNT > 0):
                                    $PDFHTML .= '<tr class="advanceRow">';
                                        $PDFHTML .= '<th>Paid to Date:</th>';
                                        $PDFHTML .= '<th>'.Number::currency($ADVANCEAMOUNT, 'GBP').'</th>';
                                    $PDFHTML .= '</tr>';
                                endif;
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<th>Due:</th>';
                                    $PDFHTML .= '<th>'.Number::currency($DUE, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                            $PDFHTML .= '</table>';
                        $PDFHTML .= '</td>';
                    $PDFHTML .= '</tr>';
                $PDFHTML .= '</table>';
                $PDFHTML .= '<div style="clear: both; width: 100%; height: 1px; margin-bottom: 40px;"></div>';

                $PDFHTML .= '<table class="pdfSummaryTable">';
                    $PDFHTML .= '<tr>';
                        $PDFHTML .= '<td>';
                            $PDFHTML .= '<div class="font-medium mb-5">Please make the payment using the following bank details</div>';
                            $PDFHTML .= '<table class="invoiceInfoTable">';
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<td class="v-top" style="width: 250px;">';
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
                                        $PDFHTML .= '<div class="mb-1">';
                                            $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Payment Ref:</span>';
                                            $PDFHTML .= '<span class="inline-block">'.$invoice->invoice_number.'</span>';
                                        $PDFHTML .= '</div>';

                                        $PDFHTML .= '<div class="font-medium mb-4 pt-9">Payment Terms</div>';
                                        $PDFHTML .= '<div class="mb-10">'.(isset($invoice->payment_term) && !empty($invoice->payment_term) ? $invoice->payment_term : '').'</div>';
                                        $PDFHTML .= '<div class="font-medium mb-4">Notes</div>';
                                        $PDFHTML .= '<div>'.(isset($invoice->notes) && !empty($invoice->notes) ? $invoice->notes : '').'</div>';
                                    $PDFHTML .= '</td>';
                                $PDFHTML .= '</tr>';
                            $PDFHTML .= '</table>';
                        $PDFHTML .= '</td>';
                        $PDFHTML .= '<td>&nbsp;</td>';
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

    public function sendEmail($invoice_id, $job_form_id){
        $user_id = Auth::user()->id;
        $invoice = Invoice::with('items', 'job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($invoice_id);
        $customerName = (isset($invoice->customer->full_name) && !empty($invoice->customer->full_name) ? $invoice->customer->full_name : '');
        $customerEmail = (isset($invoice->customer->contact->email) && !empty($invoice->customer->contact->email) ? $invoice->customer->contact->email : '');
        if(!empty($customerEmail)):
            $isNonVatCheck = ($invoice->vat_registerd == 1 ? true : false);
            $template = JobFormEmailTemplate::where('user_id', $user_id)->where('job_form_id', $job_form_id)->get()->first();
            $subject = (isset($template->subject) && !empty($template->subject) ? $template->subject : 'Job Invoice');
            $content = (isset($template->content) && !empty($template->content) ? $template->subjcontentect : '');
            if($content == ''):
                $content .= 'Hi '.$customerName.',<br/><br/>';
                $content .= 'Please check attachment for details.<br/><br/>';
                $content .= 'Thanks & Regards<br/>';
                $content .= 'Gas Safety Engineer';
            endif;
            
            $sendTo = [$customerEmail];
            $configuration = [
                'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'smtp_port' => env('MAIL_PORT', '587'),
                'smtp_username' => env('MAIL_USERNAME', 'no-reply@lcc.ac.uk'),
                'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
                'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
                
                'from_email'    => env('MAIL_FROM_ADDRESS', 'no-reply@lcc.ac.uk'),
                'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'),

            ];

            $fileName = $invoice->invoice_number.'.pdf';
            $attachmentFiles = [];
            $attachmentFiles[] = [
                "pathinfo" => 'invoices/'.$invoice->customer_job_id.'/'.$invoice->job_form_id.'/'.$fileName,
                "nameinfo" => $fileName,
                "mimeinfo" => 'application/pdf',
                "disk" => 'public'
            ];

            GCEMailerJob::dispatch($configuration, $sendTo, new GCESendMail($subject, $content, $attachmentFiles));
            return true;
        else:
            return false;
        endif;
    }


    public function approve($invoice_id)
    {
        try {
             $invoice = Invoice::findOrFail($invoice_id);
            $invoice->update([
                'status' => 'Approved'
            ]);

            return response()->json([
                    'success' => true,
                    'message' => 'Invoice successfully approved.',
                    'invoice_id' => $invoice->id
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found. The requested Invoice (ID: '.$invoice_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
       
    }


    public function approve_email($invoice_id)
    {
        try {
            $invoice = Invoice::findOrFail($invoice_id);
            
            $updateResult = $invoice->update([
                'status' => 'Approved & Sent'
            ]);

            if (!$updateResult) {
                throw new \Exception("Failed to update invoice status");
            }

            $emailSent = false;
            $emailError = null;
            
            try {
                $emailSent = $this->sendEmail($invoice->id, $invoice->job_form_id);
            } catch (\Exception $e) {
                $emailError = $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'message' => $emailSent 
                    ? 'Gas Warning Notice Certificate has been approved and emailed to the customer'
                    : 'Gas Warning Notice Certificate has been approved but email failed: ' . 
                    ($emailError ?: 'Invalid or empty email address'),
                'invoice_id' => $invoice->id
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found. The requested Invoice (ID: '.$invoice_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function download($invoice_id)
    {
        try {
            $invoice = Invoice::findOrFail($invoice_id);
            $thePdf = $this->generatePdf($invoice->id);

            return response()->json([
                    'success' => true,
                    'download_url' => $thePdf,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found. The requested Invoice (ID: '.$invoice_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
        
    }

}
