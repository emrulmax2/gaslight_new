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
use App\Models\Quote;
use App\Models\QuoteItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Exception;

class QuoteController extends Controller
{
    public function getDetails($quote_id)
    {
        try {
            $quote = Quote::with(['customer', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'items'])->findOrFail($quote_id);
            $user_id = Auth::user()->id;
            $form = JobForm::find($quote->job_form_id);
            $record = $form->slug;

            if(empty($qut->quote_number)):
                $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form->id)->orderBy('id', 'DESC')->get()->first();
                $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
                $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
                $userLastQuote = Quote::where('customer_job_id', $quote->customer_job_id)->where('job_form_id', $form->id)->where('id', '!=', $quote->id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
                $lastQuoteNo = (isset($userLastQuote->quote_number) && !empty($userLastQuote->quote_number) ? $userLastQuote->quote_number : '');

                $qutSerial = $starting_form;
                if(!empty($lastQuoteNo)):
                    preg_match("/(\d+)/", $lastQuoteNo, $quoteNumbers);
                    $qutSerial = isset($quoteNumbers[1]) ? ((int) $quoteNumbers[1]) + 1 : $starting_form;
                endif;
                $quoteNumber = $prifix . $qutSerial;
                
                Quote::where('id', $quote->id)->update(['quote_number' => $quoteNumber]);
            endif;

            $thePdf = $this->generatePdf($quote->id);
            return response()->json([
                'success' => true,
                'data' => [
                    'form' => $form,
                    'quote' => $quote,
                    'pdf_url' => $thePdf,
                    'hasInvoice' => Invoice::where('customer_job_id', $quote->customer_job_id)->get()->count()
                ]
            ]);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. The requested Quote (ID: '.$quote_id.') does not exist or may have been deleted.',
            ], 404);
            
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }


    protected function generateQuoteNumber(Quote $quote, int $user_id, JobForm $form): string
    {
        $prefixData = JobFormPrefixMumbering::where('user_id', $user_id)
            ->where('job_form_id', $form->id)
            ->orderBy('id', 'DESC')
            ->first();
        
        $prefix = $prefixData->prefix ?? '';
        $startingFrom = $prefixData->starting_from ?? 1;

        $userLastQuote = Quote::where('customer_job_id', $quote->customer_job_id)
            ->where('job_form_id', $form->id)
            ->where('created_by', $user_id)
            ->orderBy('id', 'DESC')
            ->first();
        
        $lastQuoteNo = $userLastQuote->quote_number ?? '';

        $quoteSerial = $startingFrom;
        if(!empty($lastQuoteNo)) {
            preg_match("/(\d+)/", $lastQuoteNo, $quoteNumbers);
            $quoteSerial = $quoteNumbers[1] ?? $startingFrom;
            $quoteSerial = (int)$quoteSerial + 1;
        }

        return $prefix . $quoteSerial;
    }


    public function store(Request $request)
    {
        try {
            $job_form_id = 3;

            $user = $request->user();
            $user_id = $user->id;
            $company = $user->companies->first() ?? [];

            $form = JobForm::findOrFail($job_form_id);

            $quote_id = $request->quote_id ?? 0;
            $customer_job_id = $request->job_id ?? 0;
            $customer_id = $request->customer_id;
            $customer_property_id = $request->customer_property_id;
            $property = CustomerProperty::findOrFail($customer_property_id);
            
            $nonVatInvoice = $request->non_vat_quote ?? false;
            $quoteItems = $request->quoteItems;
            $quoteDiscounts = $request->quoteDiscounts;
            $quoteNotes = $request->quoteNotes;

            if ($customer_job_id == 0) {
                $customerJob = CustomerJob::create([
                    'customer_id' => $customer_id,
                    'customer_property_id' => $customer_property_id,
                    'description' => $form->name,
                    'details' => 'Job created for '.$property->full_address,
                    'created_by' => $user_id
                ]);
                $customer_job_id = $customerJob->id;
            }

            $job = CustomerJob::findOrFail($customer_job_id);
            $quote = Quote::updateOrCreate(
                ['id' => $quote_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id], 
                [
                    'customer_id' => $customer_id,
                    'customer_job_id' => $customer_job_id,
                    'job_form_id' => $job_form_id,
                    'quote_number' => $request->quote_number,
                    'issued_date' => $request->issued_date ? date('Y-m-d', strtotime($request->issued_date)) : date('Y-m-d'),
                    'reference_no' => $job->reference_no ?? null,
                    'non_vat_quote' => $nonVatInvoice ? 1 : 0,
                    'vat_number' => $request->vat_number ?? null,
                    'notes' => $quoteNotes ?? null,
                    'payment_term' => $company->bank->payment_term ?? null,
                    'updated_by' => $user_id,
                ]
            );

            $this->checkAndUpdateRecordHistory($quote->id);

            QuoteItem::where('quote_id', $quote->id)->forceDelete();
            
            if (!empty($quoteItems)) {
                foreach ($quoteItems as $item) {
                    $units = $item['units'] ?? 0;
                    $unit_price = $item['price'] ?? 0;
                    $vat_rate = $item['vat'] ?? 0;
                    
                    $item_total = $unit_price * $units;
                    $vat_amount = ($item_total * $vat_rate) / 100;

                    QuoteItem::create([
                        'quote_id' => $quote->id,
                        'type' => 'Default',
                        'description' => $item['description'] ?? 'Invoice Item',
                        'units' => $units,
                        'unit_price' => $unit_price,
                        'vat_rate' => $vat_rate,
                        'vat_amount' => $vat_amount,
                        'created_by' => $user_id,
                        'updated_by' => $user_id,
                    ]);
                }
            }

            if (!empty($quoteDiscounts)) {
                $units = 1;
                $unit_price = $quoteDiscounts['amount'] ?? 0;
                $vat_rate = $quoteDiscounts['vat'] ?? 0;
                
                $vat_amount = ($unit_price * $vat_rate) / 100;

                QuoteItem::create([
                    'quote_id' => $quote->id,
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
                'message' => 'Quote successfully created.',
                'data' =>  Quote::with(['customer', 'job', 'form', 'items', 'user'])->findOrFail($quote->id)
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact the administrator.',
            ], 500);
        }
    }

     public function checkAndUpdateRecordHistory($record_id){
        $record = Quote::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => Quote::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => Quote::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]); 
    }

     public function generatePdf($quote_id) {
        $quote = Quote::with('items', 'job', 'job.property', 'customer', 'user', 'user.company')->find($quote_id);
        $isNonVatCheck = ($quote->vat_registerd == 1 ? true : false);
    
        $logoPath = resource_path('images/gas_safe_register.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $report_title = 'Quote of '.$quote->quote_number;
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
                                .quoteTitle{font-size: 28px; line-height: 34px; margin-bottom: 0px;}
                                .quoteRef{font-size: 20px; line-height: 26px; margin-bottom: 8px;}
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
                                $PDFHTML .= '<div class="customerName font-medium">'.$quote->customer->full_name.'</div>';
                                $PDFHTML .= '<div class="customerAddress">'.(isset($quote->customer->full_address_html) ? $quote->customer->full_address_html : '').'</div>';
                            $PDFHTML .= '</div>';
                        $PDFHTML .= '</td>';
                        $PDFHTML .= '<td class="quoteDetails text-right v-top">';
                            $PDFHTML .= '<div class="quoteTitle font-bold">Quote</div>';
                            $PDFHTML .= '<div class="quoteRef font-bold text-primary">Ref: '.$quote->quote_number.'</div>';
                            $PDFHTML .= '<div class="titleLabel mb-8">'.$quote->user->company->company_name.'</div>';
                            $PDFHTML .= '<div class="companyAddress">'.(isset($quote->user->company->full_address_html) ? $quote->user->company->full_address_html : '').'</div>';
                            if(isset($quote->user->company->company_email) && !empty($quote->user->company->company_email)):
                                $PDFHTML .= '<div>'.$quote->user->company->company_email.'</div>';
                            endif;
                            if(isset($quote->user->company->company_phone) && !empty($quote->user->company->company_phone)):
                                $PDFHTML .= '<div>'.$quote->user->company->company_phone.'</div>';
                            endif;
                            if($quote->non_vat_quote != 1):
                                $PDFHTML .= '<div class="vatNumberField pt-10 mb-1">';
                                    $PDFHTML .= '<span class="font-bold mr-3">VAT:</span>';
                                    $PDFHTML .= '<span>'.$quote->vat_number.'</span>';
                                $PDFHTML .= '</div>';
                            endif;
                            if(!empty($quote->issued_date) && !empty($quote->issued_date)):
                                $PDFHTML .= '<div class="mb-1">';
                                    $PDFHTML .= '<span class="font-bold mr-3">Date:</span>';
                                    $PDFHTML .= '<span>'.date('jS F, Y', strtotime($quote->issued_date)).'</span>';
                                $PDFHTML .= '</div>';
                            endif;
                            if(!empty($quote->issued_date) && !empty($quote->issued_date)):
                                $PDFHTML .= '<div class="mb-1">';
                                    $PDFHTML .= '<span class="font-bold mr-3">Job Ref No:</span>';
                                    $PDFHTML .= '<span>'.(isset($quote->reference_no) ? $quote->reference_no : '').'</span>';
                                $PDFHTML .= '</div>';
                            endif;
                            $PDFHTML .= '<div class="titleLabel pt-10">Job Address</div>';
                            $PDFHTML .= '<div class="companyAddress">'.(isset($quote->job->property->full_address_html) ? $quote->job->property->full_address_html : '').'</div>';
                        $PDFHTML .= '</td>';
                    $PDFHTML .= '</tr>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="table table-sm table-bordered quoteItemsTable mb-50">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="text-left">DESCRIPTION</th>';
                            $PDFHTML .= '<th class="text-right">UNITS</th>';
                            $PDFHTML .= '<th class="text-right">PRICE</th>';
                            if($quote->non_vat_quote != 1):
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
                    $ADVANCEAMOUNT = (isset($quote->advance_amount) && $quote->advance_amount > 0 ? $quote->advance_amount : 0);
                    if(isset($quote->items) && $quote->items->count() > 0):
                        foreach($quote->items as $item):
                            $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                            $unitPrice = (!empty($item->unit_price) && $item->unit_price > 0 ? $item->unit_price : 0);
                            $vatRate = (!empty($item->vat_rate) && $item->vat_rate > 0 ? $item->vat_rate : 0);
                            $vatAmount = ($unitPrice * $vatRate) / 100;
                            $lineTotal = ($quote->non_vat_quote != 1 ? ($unitPrice * $units) + $vatAmount : ($unitPrice * $units));
                            if($item->type == 'Discount'):
                                $DISCOUNTTOTAL += ($unitPrice * $units);
                                $DISCOUNTVATTOTAL += $vatAmount;
                            else:
                                $SUBTOTAL += ($unitPrice * $units);
                                $VATTOTAL += $vatAmount;
                            endif;

                            $PDFHTML .= '<tr>';
                                $PDFHTML .= '<td>';
                                    $PDFHTML .= (isset($item->description) && !empty($item->description) ? $item->description : 'Quote Item');
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-80 text-right">';
                                    $PDFHTML .= $units;
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="w-80 text-right font-medium">';
                                    $PDFHTML .= Number::currency($unitPrice, 'GBP');
                                $PDFHTML .= '</td>';
                                if($quote->non_vat_quote != 1):
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
                    $TOTAL = ($quote->non_vat_quote != 1 ? $SUBTOTAL + $VATTOTAL : $SUBTOTAL);
                    $DUE = $TOTAL - $ADVANCEAMOUNT;
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table class="pdfSummaryTable">';
                    $PDFHTML .= '<tr>';
                        $PDFHTML .= '<td>&nbsp;</td>';
                        $PDFHTML .= '<td class="calculationColumns v-top">';
                            $PDFHTML .= '<table class="calculationTable">';
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<th>Subtotal:</th>';
                                    $PDFHTML .= '<th>'.Number::currency($SUBTOTAL, 'GBP').'</th>';
                                $PDFHTML .= '</tr>';
                                if($quote->non_vat_quote != 1):
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
                            $PDFHTML .= '<table class="quoteInfoTable">';
                                $PDFHTML .= '<tr>';
                                    $PDFHTML .= '<td class="v-top" style="width: 250px;">';
                                        if((isset($quote->user->company->bank->bank_name) && !empty($quote->user->company->bank->bank_name)) || 
                                            (isset($quote->user->company->bank->name_on_account) && !empty($quote->user->company->bank->name_on_account)) || 
                                            (isset($quote->user->company->bank->sort_code) && !empty($quote->user->company->bank->sort_code)) || 
                                            (isset($quote->user->company->bank->account_number) && !empty($quote->user->company->bank->account_number))):
                                            $PDFHTML .= '<div class="font-medium mb-5">Bank Details</div>';
                                            if(isset($quote->user->company->bank->bank_name) && !empty($quote->user->company->bank->bank_name)):
                                                $PDFHTML .= '<div class="mb-1">';
                                                    $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Bank Name:</span>';
                                                    $PDFHTML .= '<span class="inline-block">'.$quote->user->company->bank->bank_name.'</span>';
                                                $PDFHTML .= '</div>';
                                            endif;
                                            if(isset($quote->user->company->bank->name_on_account) && !empty($quote->user->company->bank->name_on_account)):
                                                $PDFHTML .= '<div class="mb-1">';
                                                    $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Account Name:</span>';
                                                    $PDFHTML .= '<span class="inline-block">'.$quote->user->company->bank->name_on_account.'</span>';
                                                $PDFHTML .= '</div>';
                                            endif;
                                            if(isset($quote->user->company->bank->sort_code) && !empty($quote->user->company->bank->sort_code)):
                                                $PDFHTML .= '<div class="mb-1">';
                                                    $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Sort Code:</span>';
                                                    $PDFHTML .= '<span class="inline-block">'.$quote->user->company->bank->sort_code.'</span>';
                                                $PDFHTML .= '</div>';
                                            endif;
                                            if(isset($quote->user->company->bank->account_number) && !empty($quote->user->company->bank->account_number)):
                                                $PDFHTML .= '<div class="mb-1">';
                                                    $PDFHTML .= '<span class="font-medium text-slate-400 inline-block w-140">Account Number:</span>';
                                                    $PDFHTML .= '<span class="inline-block">'.$quote->user->company->bank->account_number.'</span>';
                                                $PDFHTML .= '</div>';
                                            endif;
                                        endif;

                                        if(isset($quote->payment_term) && !empty($quote->payment_term)):
                                            $PDFHTML .= '<div class="font-medium mb-4 pt-9">Payment Terms</div>';
                                            $PDFHTML .= '<div class="mb-10">'.(isset($quote->payment_term) && !empty($quote->payment_term) ? $quote->payment_term : '').'</div>';
                                        endif;
                                        if(isset($quote->notes) && !empty($quote->notes)):
                                            $PDFHTML .= '<div class="font-medium mb-4">Notes</div>';
                                            $PDFHTML .= '<div>'.(isset($quote->notes) && !empty($quote->notes) ? $quote->notes : '').'</div>';
                                        endif;
                                    $PDFHTML .= '</td>';
                                $PDFHTML .= '</tr>';
                            $PDFHTML .= '</table>';
                        $PDFHTML .= '</td>';
                        $PDFHTML .= '<td>&nbsp;</td>';
                    $PDFHTML .= '</tr>';
                $PDFHTML .= '</table>';

            $PDFHTML .= '</body>';
        $PDFHTML .= '</html>';


        $fileName = $quote->quote_number.'.pdf';
        $pdf = Pdf::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true, 'dpi' => '110'])
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);
        $content = $pdf->output();
        Storage::disk('public')->put('quotes/'.$quote->customer_job_id.'/'.$quote->job_form_id.'/'.$fileName, $content );

        return Storage::disk('public')->url('quotes/'.$quote->customer_job_id.'/'.$quote->job_form_id.'/'.$fileName);
        
    }


    public function approve($quote_id)
    {
        try {
            $quote = Quote::findOrFail($quote_id);
            $quote->update([
                'status' => 'Approved'
            ]);

            return response()->json([
                    'success' => true,
                    'message' => 'Quote successfully approved.',
                    'quote_id' => $quote->id
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. The requested Quote (ID: '.$quote_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
   
    }

    public function sendEmail($quote_id, $job_form_id){
        $user_id = Auth::user()->id;
        $quote = Quote::with('items', 'job', 'job.property', 'customer', 'customer.contact', 'user', 'user.company')->find($quote_id);
        $customerName = (isset($quote->customer->full_name) && !empty($quote->customer->full_name) ? $quote->customer->full_name : '');
        $customerEmail = (isset($quote->customer->contact->email) && !empty($quote->customer->contact->email) ? $quote->customer->contact->email : '');
        if(!empty($customerEmail)):
            $isNonVatCheck = ($quote->vat_registerd == 1 ? true : false);
            $template = JobFormEmailTemplate::where('user_id', $user_id)->where('job_form_id', $job_form_id)->get()->first();
            $subject = (isset($template->subject) && !empty($template->subject) ? $template->subject : 'Job Quote');
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

            $fileName = $quote->quote_number.'.pdf';
            $attachmentFiles = [];
            $attachmentFiles[] = [
                "pathinfo" => 'quotes/'.$quote->customer_job_id.'/'.$quote->job_form_id.'/'.$fileName,
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


    public function approve_email($quote_id)
    {
        try {
            $quote = Quote::findOrFail($quote_id);
            $quote->update([
                'status' => 'Approved & Sent'
            ]);
                
            $email = $this->sendEmail($quote->id, $quote->job_form_id);
            $message = (!$email ? 'Quote has been approved. Email cannot be sent due to an invalid or empty email address.' : 'Quote has been approved and a copy of the certificate mailed to the customer');

            return response()->json([
                    'success' => true,
                    'message' => $message,
                    'quote_id' => $quote->id
                ], 200);
        }  catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. The requested Quote (ID: '.$quote_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }

    public function download($quote_id)
    {
        try {
            $quote = Quote::findOrFail($quote_id);
            $thePdf = $this->generatePdf($quote->id);

            return response()->json([
                    'success' => true,
                    'download_url' => $thePdf,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. The requested Quote (ID: '.$quote_id.') does not exist or may have been deleted.',
            ], 404);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
       

    }

    public function convertToInvoice(Request $request){
        $quote = Quote::with('items')->find($request->quote_id);
        $invoiceForm = JobForm::where('slug', 'invoice')->get()->first();

        $customer_job_id = $quote->customer_job_id;
        $customer_id = $quote->customer_id;
        $job_form_id = $quote->job_form_id;
        $user_id = (isset($quote->created_by) && $quote->created_by > 0 ? $quote->created_by : auth()->user()->id);

        $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $job_form_id)->orderBy('id', 'DESC')->get()->first();
        $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
        $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
        $userLastInvoice = Invoice::where('customer_job_id', $customer_job_id)->where('job_form_id', $job_form_id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
        $lastInvoiceNo = (isset($userLastInvoice->invoice_number) && !empty($userLastInvoice->invoice_number) ? $userLastInvoice->invoice_number : '');

        $invSerial = $starting_form;
        if(!empty($lastInvoiceNo)):
            preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
            $invSerial = (int) $invoiceNumbers[1] + 1;
        endif;
        $invoiceNumber = $prifix.str_pad($invSerial, 6, '0', STR_PAD_LEFT);

        $data = [
            'customer_id' => $quote->customer_id,
            'customer_job_id' => $quote->customer_job_id,
            'job_form_id' => (isset($invoiceForm->id) && $invoiceForm->id > 0 ? $invoiceForm->id : 4),
            'invoice_number' => $invoiceNumber,
            'issued_date' => (isset($quote->issued_date) && !empty($quote->issued_date) ? date('Y-m-d', strtotime($request->issued_date)) : date('Y-m-d')),
            'reference_no' => (isset($quote->reference_no) && !empty($quote->reference_no) ? $quote->reference_no : null),
            'non_vat_invoice' => (isset($quote->non_vat_invoice) && $quote->non_vat_invoice > 0 ? $quote->non_vat_invoice : 0),
            'vat_number' => (isset($quote->vat_number) && $quote->vat_number > 0 ? $quote->vat_number : null),
            'advance_amount' => null,
            'payment_method_id' => null,
            'advance_date' => null,
            'notes' => (!empty($quote->notes) ? $quote->notes : null),
            'payment_term' => (!empty($quote->payment_term) ? $quote->payment_term : null),
            'status' => 'Draft',
            
            'created_by' => $user_id
        ];
        $invoice = Invoice::create($data);
        if($invoice->id):
            if(isset($quote->items) && $quote->items->count() > 0):
                foreach($quote->items as $item):
                    $type = (isset($item['type']) && !empty($item['type']) ? $item['type'] : 'Default');
                    $units = (isset($item['units']) && $item['units'] > 0 ? $item['units'] : 0);
                    $unit_price = (isset($item['unit_price']) && $item['unit_price'] > 0 ? $item['unit_price'] : 0);
                    $vat_rate = (isset($item['vat_rate']) && $item['vat_rate'] > 0 ? $item['vat_rate'] : 0);
                    $vat_amount = (isset($item['vat_amount']) && $item['vat_amount'] > 0 ? $item['vat_amount'] : 0);
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'type' => $type,
                        'description' => (isset($item['descritpion']) && !empty($item['descritpion']) ? $item['descritpion'] : 'Invoice Item'),
                        'units' => $units,
                        'unit_price' => $unit_price,
                        'vat_rate' => $vat_rate,
                        'vat_amount' => $vat_amount,
                        
                        'created_by' => $user_id
                    ]);
                endforeach;
            endif;

            $record = Invoice::with('customer', 'customer.contact', 'job', 'job.property')->find($invoice->id);
            $invoiceItems = InvoiceItem::where('invoice_id', $invoice->id)->where('type', 'Default')->orderBy('id', 'ASC')->get();
            $discountItems = InvoiceItem::where('invoice_id', $invoice->id)->where('type', 'Discount')->orderBy('id', 'ASC')->get()->first();
            $nonVatInvoice = (isset($record->non_vat_invoice) && $record->non_vat_invoice == 1 ? true : false);

            $data = [
                'invoice_id' => $record->id,
                'invoiceDetails' => [
                    'invoice_number' => (isset($record->invoice_number) && !empty($record->invoice_number) ? $record->invoice_number : ''),
                    'issued_date' => (isset($record->issued_date) && !empty($record->issued_date) ? date('d-m-Y', strtotime($record->issued_date)) : ''),
                    'non_vat_invoice' => (isset($record->non_vat_invoice) && !empty($record->non_vat_invoice) ? $record->non_vat_invoice : ''),
                    'vat_number' => (isset($record->vat_number) && !empty($record->vat_number) ? $record->vat_number : ''),
                ],
                'invoiceNotes' => (isset($record->notes) && !empty($record->notes) ? $record->notes : ''),
                'job' => $record->job,
                'customer' => $record->customer,
                'job_address' => $record->job->property,
                'occupant' => [
                    'customer_property_occupant_id' => $record->job->property->id,
                    'occupant_name' => (isset($record->job->property->occupant_name) && !empty($record->job->property->occupant_name) ? $record->job->property->occupant_name : ''),
                    'occupant_email' => (isset($record->job->property->occupant_email) && !empty($record->job->property->occupant_email) ? $record->job->property->occupant_email : ''),
                    'occupant_phone' => (isset($record->job->property->occupant_phone) && !empty($record->job->property->occupant_phone) ? $record->job->property->occupant_phone : ''),
                ],
                'invoiceNumber' => (isset($record->invoice_number) && !empty($record->invoice_number) ? $record->invoice_number : '')
            ];
    
            if($invoiceItems->count() > 0):
                $i = 1;
                foreach($invoiceItems as $item):
                    $units = (isset($item->units) && !empty($item->units) ? $item->units : 1);
                    $price = (isset($item->unit_price) && !empty($item->unit_price) ? $item->unit_price : 0);
                    $vat_rat = (isset($item->vat_rate) && !empty($item->vat_rate) ? $item->vat_rate : 0);
    
                    $itemTotal = $units * $price;
                    $vatTotal = ($itemTotal * $vat_rat) / 100;
                    $lineTotal = ($nonVatInvoice ? $itemTotal : $itemTotal + $vatTotal);
    
                    $data['invoiceItems'][$i] = [
                        'inv_item_title' => (isset($item->description) && !empty($item->description) ? $item->description : $i.' Line Item'),
                        'description' => (isset($item->description) && !empty($item->description) ? $item->description : $i.' Line Item'),
                        'units' => (isset($item->units) && !empty($item->units) ? $item->units : 1),
                        'price' => (isset($item->unit_price) && !empty($item->unit_price) ? $item->unit_price : 0),
                        'vat' => (isset($item->vat_rate) && !empty($item->vat_rate) ? $item->vat_rate : 0),
                        'line_total' => $lineTotal,
                    ];
                    $i++;
                endforeach;
                $data['invoiceItemsCount'] = $invoiceItems->count();
            endif;
    
            if(isset($discountItems->id) && $discountItems->id > 0):
                $data['invoiceDiscounts'] = [
                    'inv_item_title' => 'Discount',
                    'amount' => (isset($discountItems->unit_price) && $discountItems->unit_price > 0 ? $discountItems->unit_price : 0),
                    'vat' => (isset($discountItems->vat_rate) && $discountItems->vat_rate > 0 ? $discountItems->vat_rate : ''),
                ];
            endif;
    
            if(isset($record->advance_amount) && $record->advance_amount > 0):
                $data['invoiceAdvance'] = [
                    'advance_amount' => (isset($record->advance_amount) && $record->advance_amount > 0 ? $record->advance_amount : ''),
                    'payment_method_id' => (isset($record->payment_method_id) && $record->payment_method_id > 0 ? $record->payment_method_id : ''),
                    'advance_pay_date' => (isset($record->advance_date) && !empty($record->advance_date) ? date('d-m-Y', strtotime($record->advance_date)) : ''),
                ];
            endif;
    
            return response()->json([
                'success' => true,
                'message' => 'Quote successfully converted to invoice.',
                'data' => $data
                ], 200);
        else:
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator.'
            ], 422);
        endif;
    }
}
