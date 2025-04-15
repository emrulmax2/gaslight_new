<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\ApplianceFlueType;
use App\Models\ApplianceLocation;
use App\Models\ApplianceMake;
use App\Models\ApplianceTimeTemperatureHeating;
use App\Models\ApplianceType;
use App\Models\BoilerBrand;
use App\Models\Color;
use App\Models\CommissionDecommissionWorkType;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasBreakdownRecord;
use App\Models\GasBreakdownRecordAppliance;
use App\Models\GasSafetyRecord;
use App\Models\GasSafetyRecordAppliance;
use App\Models\GasServiceRecord;
use App\Models\GasServiceRecordAppliance;
use App\Models\GasWarningClassification;
use App\Models\GasWarningNotice;
use App\Models\GasWarningNoticeAppliance;
use App\Models\GasBoilerSystemCommissioningChecklist;
use App\Models\GasBoilerSystemCommissioningChecklistAppliance;
use App\Models\GasCommissionDecommissionRecord;
use App\Models\GasCommissionDecommissionRecordAppliance;
use App\Models\GasJobSheetRecord;
use App\Models\GasJobSheetRecordDetail;
use App\Models\GasJobSheetRecordDocument;
use App\Models\GasPowerFlushRecord;
use App\Models\GasPowerFlushRecordChecklist;
use App\Models\GasPowerFlushRecordRediator;
use App\Models\GasUnventedHotWaterCylinderRecord;
use App\Models\GasUnventedHotWaterCylinderRecordInspection;
use App\Models\GasUnventedHotWaterCylinderRecordSystem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobForm;
use App\Models\JobFormPrefixMumbering;
use App\Models\PaymentMethod;
use App\Models\PowerflushCirculatorPumpLocation;
use App\Models\PowerflushCylinderType;
use App\Models\PowerflushPipeworkType;
use App\Models\PowerflushSystemType;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\RadiatorType;
use App\Models\Relation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index(){
        return view('app.records.index', [
            'title' => 'Create New Record - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Create Records', 'href' => 'javascript:void(0);'],
            ],
            'forms' => JobForm::with('childs')->where('parent_id', 0)->where('active', 1)->orderBy('id', 'ASC')->get()
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
            $data['signature'] = isset($GasSafetyRecord->signature) ? Storage::disk('public')->url($GasSafetyRecord->signature->filename) : '';
        elseif($record == 'gas_warning_notice'): 
            $gasWarningNotice = GasWarningNotice::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();            
            $gwn_id = (isset($gasWarningNotice->id) && $gasWarningNotice->id > 0 ? $gasWarningNotice->id : 0);
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flue_types'] = ApplianceFlueType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['classifications'] = GasWarningClassification::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gwn'] = $gasWarningNotice;
            $data['gwna1'] = GasWarningNoticeAppliance::with('make', 'type')->where('gas_warning_notice_id', $gwn_id)->where('appliance_serial', 1)->get()->first();
            $data['signature'] = isset($gasWarningNotice->signature) ? Storage::disk('public')->url($gasWarningNotice->signature->filename) : '';
        elseif($record == 'gas_service_record'): 
            $gasServiceRecord = GasServiceRecord::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();            
            $gwn_id = (isset($gasServiceRecord->id) && $gasServiceRecord->id > 0 ? $gasServiceRecord->id : 0);
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flue_types'] = ApplianceFlueType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['classifications'] = GasWarningClassification::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gsr'] = $gasServiceRecord;
            $data['gsra1'] = GasServiceRecordAppliance::with('make', 'type')->where('gas_service_record_id', $gwn_id)->where('appliance_serial', 1)->get()->first();
            $data['signature'] = isset($gasServiceRecord->signature) ? Storage::disk('public')->url($gasServiceRecord->signature->filename) : '';
        elseif($record == 'gas_breakdown_record'): 
            $gasBreakdownRecord = GasBreakdownRecord::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();            
            $gbr_id = (isset($gasBreakdownRecord->id) && $gasBreakdownRecord->id > 0 ? $gasBreakdownRecord->id : 0);
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flue_types'] = ApplianceFlueType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['classifications'] = GasWarningClassification::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gbr'] = $gasBreakdownRecord;
            $data['gbra1'] = GasBreakdownRecordAppliance::with('make', 'type')->where('gas_breakdown_record_id', $gbr_id)->where('appliance_serial', 1)->get()->first();
            $data['signature'] = isset($gasBreakdownRecord->signature) ? Storage::disk('public')->url($gasBreakdownRecord->signature->filename) : '';
        elseif($record == 'gas_boiler_system_commissioning_checklist'): 
            $gasBoilerSCC = GasBoilerSystemCommissioningChecklist::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();            
            $gbscc_id = (isset($gasBoilerSCC->id) && $gasBoilerSCC->id > 0 ? $gasBoilerSCC->id : 0);
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['timerTemp'] = ApplianceTimeTemperatureHeating::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gbscc'] = $gasBoilerSCC;
            $data['gbscca1'] = GasBoilerSystemCommissioningChecklistAppliance::with('make', 'temperature')->where('gas_boiler_system_commissioning_checklist_id', $gbscc_id)->where('appliance_serial', 1)->get()->first();
            $data['signature'] = isset($gasBoilerSCC->signature) ? Storage::disk('public')->url($gasBoilerSCC->signature->filename) : '';
        elseif($record == 'power_flush_record'):
            $gasePowerFlashRecord = GasPowerFlushRecord::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();            
            $gpfr_id = (isset($gasePowerFlashRecord->id) && $gasePowerFlashRecord->id > 0 ? $gasePowerFlashRecord->id : 0);
            $data['flush_types'] = PowerflushSystemType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flush_cylinder'] = PowerflushCylinderType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flush_pipework'] = PowerflushPipeworkType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['flush_pump_location'] = PowerflushCirculatorPumpLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['radiator_type'] = RadiatorType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['color'] = Color::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['locations'] = ApplianceLocation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['boilers'] = BoilerBrand::orderBy('name', 'ASC')->get();
            $data['types'] = ApplianceType::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['classifications'] = GasWarningClassification::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gpfr'] = $gasePowerFlashRecord;
            $data['gpfrc'] = GasPowerFlushRecordChecklist::with('make', 'type')->where('gas_power_flush_record_id', $gpfr_id)->get()->first();
            $data['gpfrr'] = GasPowerFlushRecordRediator::where('gas_power_flush_record_id', $gpfr_id)->orderBy('id', 'ASC')->get();
            $data['signature'] = isset($gasePowerFlashRecord->signature) ? Storage::disk('public')->url($gasePowerFlashRecord->signature->filename) : '';
        elseif($record == 'installation_commissioning_decommissioning_record'):
            $gasComDecRecord = GasCommissionDecommissionRecord::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();            
            $gcdr_id = (isset($gasComDecRecord->id) && $gasComDecRecord->id > 0 ? $gasComDecRecord->id : 0);
            $data['worktype'] = CommissionDecommissionWorkType::where('active', 1)->orderBy('id', 'ASC')->get();
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gcdr'] = $gasComDecRecord;
            $data['gcdra1'] = GasCommissionDecommissionRecordAppliance::with('gcdrawt')->where('gas_commission_decommission_record_id', $gcdr_id)->where('appliance_serial', 1)->get()->first();
            $data['signature'] = isset($gasComDecRecord->signature) ? Storage::disk('public')->url($gasComDecRecord->signature->filename) : '';
        elseif($record == 'unvented_hot_water_cylinders'):
            $gasUnventedHWCRecord = GasUnventedHotWaterCylinderRecord::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();            
            $guhwcr_id = (isset($gasUnventedHWCRecord->id) && $gasUnventedHWCRecord->id > 0 ? $gasUnventedHWCRecord->id : 0);
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['guhwcr'] = $gasUnventedHWCRecord;
            $data['guhwcrs'] = GasUnventedHotWaterCylinderRecordSystem::with('guhwcr')->where('gas_unvented_hot_water_cylinder_record_id', $guhwcr_id)->orderBy('id', 'DESC')->get()->first();
            $data['guhwcri'] = GasUnventedHotWaterCylinderRecordInspection::with('guhwcr')->where('gas_unvented_hot_water_cylinder_record_id', $guhwcr_id)->orderBy('id', 'DESC')->get()->first();
            $data['signature'] = isset($gasUnventedHWCRecord->signature) ? Storage::disk('public')->url($gasUnventedHWCRecord->signature->filename) : '';
        elseif($record == 'job_sheet'):        
            $gasJobSheetRecord = GasJobSheetRecord::where('customer_job_id', $job->id)->where('job_form_id', $form->id)->get()->first();            
            $gjsr_id = (isset($gasJobSheetRecord->id) && $gasJobSheetRecord->id > 0 ? $gasJobSheetRecord->id : 0);
            $data['relations'] = Relation::where('active', 1)->orderBy('name', 'ASC')->get();
            $data['gjsr'] = $gasJobSheetRecord;
            $data['gjsrd'] = GasJobSheetRecordDetail::where('gas_job_sheet_record_id', $gjsr_id)->get()->first();
            $data['gjsrdc'] = GasJobSheetRecordDocument::where('gas_job_sheet_record_id', $gjsr_id)->orderBy('id', 'ASC')->get();
            $data['signature'] = isset($gasJobSheetRecord->signature) ? Storage::disk('public')->url($gasJobSheetRecord->signature->filename) : '';
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
            $existingRD = ExistingRecordDraft::where('model_type', Invoice::class)->where('model_id', $invoice->id)->get();
            if($existingRD->isEmpty()):
                ExistingRecordDraft::create([
                    'customer_id' => $invoice->customer_id,
                    'customer_job_id' => $invoice->customer_job_id,
                    'job_form_id' => $invoice->job_form_id,
                    'model_type' => Invoice::class,
                    'model_id' => $invoice->id,

                    'created_by' => $invoice->created_by,
                ]);
            endif;
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

                ExistingRecordDraft::create([
                    'customer_id' => $job->customer_id,
                    'customer_job_id' => $job_id,
                    'job_form_id' => $form_id,
                    'model_type' => Invoice::class,
                    'model_id' => $invoice->id,

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
            $existingRD = ExistingRecordDraft::where('model_type', Quote::class)->where('model_id', $quote->id)->get();
            if($existingRD->isEmpty()):
                ExistingRecordDraft::create([
                    'customer_id' => $quote->customer_id,
                    'customer_job_id' => $quote->customer_job_id,
                    'job_form_id' => $quote->job_form_id,
                    'model_type' => Quote::class,
                    'model_id' => $quote->id,

                    'created_by' => $quote->created_by,
                ]);
            endif;

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

                ExistingRecordDraft::create([
                    'customer_id' => $job->customer_id,
                    'customer_job_id' => $job_id,
                    'job_form_id' => $form_id,
                    'model_type' => Quote::class,
                    'model_id' => $quote->id,

                    'created_by' => $user_id,
                ]);

                return $quote;
            else:
                return [];
            endif;
        endif;
    }

    public function storeJobAddress(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);

        $data = [
            'address_line_1' => (!empty($request->job_address_line_1) ? $request->job_address_line_1 : null),
            'address_line_2' => (!empty($request->job_address_line_2) ? $request->job_address_line_2 : null),
            'postal_code' => (!empty($request->job_postal_code) ? $request->job_postal_code : null),
            'state' => (!empty($request->job_state) ? $request->job_state : null),
            'city' => (!empty($request->job_city) ? $request->job_city : null),
            'country' => (!empty($request->job_country) ? $request->job_country : null),
            'latitude' => (!empty($request->job_latitude) ? $request->job_latitude : null),
            'longitude' => (!empty($request->job_longitude) ? $request->job_longitude : null),
            'occupant_name' => (!empty($request->occupant_name) ? $request->occupant_name : null),
            'occupant_email' => (!empty($request->occupant_email) ? $request->occupant_email : null),
            'occupant_phone' => (!empty($request->occupant_phone) ? $request->occupant_phone : null),

            'updated_by' => auth()->user()->id,
        ];
        $jobAddress = CustomerProperty::where('id', $job->customer_property_id)->update($data);

        return response()->json(['msg' => 'Job address successfully updated.'], 200);
    }

    public function storeCustomer(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);

        $data = [
            'full_name' => (!empty($request->customer_name) ? $request->customer_name : (isset($job->customer->full_name) && !empty($job->customer->full_name) ? $job->customer->full_name : null)),
            'company_name' => (!empty($request->customer_company) ? $request->customer_company : null),
            'address_line_1' => (!empty($request->customer_address_line_1) ? $request->customer_address_line_1 : null),
            'address_line_2' => (!empty($request->customer_address_line_2) ? $request->customer_address_line_2 : null),
            'city' => (!empty($request->customer_city) ? $request->customer_city : null),
            'state' => (!empty($request->customer_state) ? $request->customer_state : null),
            'postal_code' => (!empty($request->customer_postal_code) ? $request->customer_postal_code : null),
            'country' => (!empty($request->customer_country) ? $request->customer_country : null),
            'latitude' => (!empty($request->customer_latitude) ? $request->customer_latitude : null),
            'longitude' => (!empty($request->customer_longitude) ? $request->customer_longitude : null),

            'updated_by' => auth()->user()->id,
        ];
        $customer = Customer::where('id', $job->customer_id)->update($data);
        $customerContact = CustomerContactInformation::where('customer_id', $job->customer_id)->update(['phone' => (!empty($request->customer_phone) ? $request->customer_phone : null), 'updated_by' => auth()->user()->id]);

        return response()->json(['msg' => 'Customer Details successfully updated.'], 200);
    }
}
