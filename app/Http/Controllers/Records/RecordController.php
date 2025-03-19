<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\ApplianceFlueType;
use App\Models\ApplianceLocation;
use App\Models\ApplianceMake;
use App\Models\ApplianceType;
use App\Models\BoilerBrand;
use App\Models\Company;
use App\Models\CustomerJob;
use App\Models\GasSafetyRecord;
use App\Models\GasSafetyRecordAppliance;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobForm;
use App\Models\JobFormPrefixMumbering;
use App\Models\PaymentMethod;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Relation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Creagia\LaravelSignPad\Signature;

class RecordController extends Controller
{
    public function index(){
        return view('app.records.index', [
            'title' => 'Create New Record - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Create Records', 'href' => 'javascript:void(0);'],
            ],
            'forms' => JobForm::with('childs')->where('parent_id', 0)->orderBy('id', 'ASC')->get()
        ]);
    }


    public function records($record, CustomerJob $job){
        $job->load(['customer', 'customer.contact', 'property']);
        $form = JobForm::where('slug', $record)->get()->first();
        $data = [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => ucfirst($record), 'href' => 'javascript:void(0);'],
            ],
            'record' => $record,
            'form' => $form,
            'job' => $job,
            'company' => Company::with('bank')->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get()->first(),
        ];
        if($record == 'invoice'):
            $data['invoice'] = $this->jobInvoiceCheck($job->id, $form->id);
            $data['methods'] = PaymentMethod::where('active', 1)->orderBy('name', 'asc')->get();
        elseif($record == 'quote'):
            $data['quote'] = $this->jobQuoteCheck($job->id, $form->id);
            $data['hasInvoice'] = Invoice::where('customer_job_id', $job->id)->get()->count();
        elseif($record == 'homeowner_gas_safety_record'):
            $GasSafetyRecord = GasSafetyRecord::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();
            //$GasSafetyRecord->signature;
            
            $gsr_id = (isset($GasSafetyRecord->id) && $GasSafetyRecord->id > 0 ? $GasSafetyRecord->id : 0);
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flue_types'] = ApplianceFlueType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gsr'] = $GasSafetyRecord;
            $data['gsra1'] = GasSafetyRecordAppliance::with('make', 'type')->where('gas_safety_record_id', $gsr_id)->where('appliance_serial', 1)->get()->first();
            $data['gsra2'] = GasSafetyRecordAppliance::with('make', 'type')->where('gas_safety_record_id', $gsr_id)->where('appliance_serial', 2)->get()->first();
            $data['gsra3'] = GasSafetyRecordAppliance::with('make', 'type')->where('gas_safety_record_id', $gsr_id)->where('appliance_serial', 3)->get()->first();
            $data['gsra4'] = GasSafetyRecordAppliance::with('make', 'type')->where('gas_safety_record_id', $gsr_id)->where('appliance_serial', 4)->get()->first();
            $data['signature'] = $GasSafetyRecord->signature ? Storage::disk('public')->url($GasSafetyRecord->signature->filename) : '';
        endif;


        return view('app.records.'.$record.'.index', $data);
    }

    protected function jobInvoiceCheck($job_id, $form_id){
        $job = CustomerJob::find($job_id);
        $user_id = auth()->user()->id;
        $company = Company::with('bank')->where('user_id', $user_id)->get()->first();
        $invoice = Invoice::with('items')->where('customer_job_id', $job_id)->where('job_form_id', $form_id)
                    ->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
        if(isset($invoice->id) && $invoice->id > 0):
            return $invoice;
        else:
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form_id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastInvoice = Invoice::where('customer_job_id', $job_id)->where('job_form_id', $form_id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
            $lastInvoiceNo = (isset($userLastInvoice->invoice_number) && !empty($userLastInvoice->invoice_number) ? $userLastInvoice->invoice_number : '');

            $invSerial = $starting_form;
            if(!empty($lastInvoiceNo)):
                preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
                $invSerial = (int) $invoiceNumbers[1] + 1;
            endif;
            $invoiceNumber = $prifix.str_pad($invSerial, 6, '0', STR_PAD_LEFT);

            $data = [
                'customer_id' => $job->customer_id,
                'customer_job_id' => $job_id,
                'job_form_id' => $form_id,
                'invoice_number' => $invoiceNumber,
                'issued_date' => date('Y-m-d'),
                'reference_no' => (isset($job->reference_no) && !empty($job->reference_no) ? $job->reference_no : null),
                'non_vat_invoice' => (isset($company->vat_number) && !empty($company->vat_number) ? 0 : 1),
                'vat_number' => (isset($company->vat_number) && !empty($company->vat_number) ? $company->vat_number : null),
                'payment_term' => (isset($company->bank->payment_term) && !empty($company->bank->payment_term) ? $company->bank->payment_term : null),
                'status' => 'Draft',
                
                'created_by' => $user_id
            ];
            $invoice = Invoice::create($data);
            if($invoice->id):
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'type' => 'Default',
                    'description' => (isset($job->description) && !empty($job->description) ? $job->description : 'Invoice Item'),
                    'units' => 1,
                    'unit_price' => (!empty($job->estimated_amount) && $job->estimated_amount > 0 ? $job->estimated_amount : 0),
                    'vat_rate' => 20,
                    'vat_amount' => (!empty($job->estimated_amount) && $job->estimated_amount > 0 ? ($job->estimated_amount * 20) / 100 : 0),
                    
                    'created_by' => $user_id,
                ]);

                return $invoice;
            else:
                return [];
            endif;
        endif;
    }

    protected function jobQuoteCheck($job_id, $form_id){
        $job = CustomerJob::find($job_id);
        $user_id = auth()->user()->id;
        $company = Company::with('bank')->where('user_id', $user_id)->get()->first();
        $quote = Quote::with('items')->where('customer_job_id', $job_id)->where('job_form_id', $form_id)
                    ->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
        if(isset($quote->id) && $quote->id > 0):
            return $quote;
        else:
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form_id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastQuote = Quote::where('customer_job_id', $job_id)->where('job_form_id', $form_id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
            $lastQuoteNo = (isset($userLastQuote->quote_number) && !empty($userLastQuote->quote_number) ? $userLastQuote->quote_number : '');

            $quoteSerial = $starting_form;
            if(!empty($lastQuoteNo)):
                preg_match("/(\d+)/", $lastQuoteNo, $quoteNumbers);
                $quoteSerial = (int) $quoteNumbers[1] + 1;
            endif;
            $quoteNumber = $prifix.str_pad($quoteSerial, 6, '0', STR_PAD_LEFT);

            $data = [
                'customer_id' => $job->customer_id,
                'customer_job_id' => $job_id,
                'job_form_id' => $form_id,
                'quote_number' => $quoteNumber,
                'issued_date' => date('Y-m-d'),
                'reference_no' => (isset($job->reference_no) && !empty($job->reference_no) ? $job->reference_no : null),
                'non_vat_quote' => (isset($company->vat_number) && !empty($company->vat_number) ? 0 : 1),
                'vat_number' => (isset($company->vat_number) && !empty($company->vat_number) ? $company->vat_number : null),
                'payment_term' => (isset($company->bank->payment_term) && !empty($company->bank->payment_term) ? $company->bank->payment_term : null),
                'status' => 'Draft',
                
                'created_by' => $user_id
            ];
            $quote = Quote::create($data);
            if($quote->id):
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'type' => 'Default',
                    'description' => (isset($job->description) && !empty($job->description) ? $job->description : 'Invoice Item'),
                    'units' => 1,
                    'unit_price' => (!empty($job->estimated_amount) && $job->estimated_amount > 0 ? $job->estimated_amount : 0),
                    'vat_rate' => 20,
                    'vat_amount' => (!empty($job->estimated_amount) && $job->estimated_amount > 0 ? ($job->estimated_amount * 20) / 100 : 0),
                    
                    'created_by' => $user_id,
                ]);

                return $quote;
            else:
                return [];
            endif;
        endif;
    }
}
