<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobForm;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

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
            $invoices = Invoice::with('items')->findOrFail($invoice_id);
            
            return response()->json([
                'success' => true,
                'data' => $invoices
            ]);
        } catch (Exception $e) {
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
                    'data' => [
                        'invoice_id' => $invoice->id,
                    ]
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
                'message' => 'Something went wrong. Please try again later or contact with the administrator.'
            ], 500);
        }
    }


}
