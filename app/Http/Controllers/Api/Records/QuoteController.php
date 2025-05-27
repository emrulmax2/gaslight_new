<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\JobForm;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Http\Request;
use Exception;

class QuoteController extends Controller
{
    public function getDetails($quote_id)
    {
        try {
            $quotes = Quote::with(['items'])->findOrFail($quote_id);

            return response()->json([
                'success' => true,
                'data' => $quotes
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quote not found. . The requested Quote (ID: '.$quote_id.') does not exist or may have been deleted.',
            ], 500);
        }
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
                'quote_id' => $quote->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact the administrator.',
                'error' => $e->getMessage()
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
}
