<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\GasWarningNotice;
use App\Models\GasWarningNoticeAppliance;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;

class GasWarningNoticeController extends Controller
{
    public function storeAppliance(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $serial = $request->appliance_serial;
        $appliance = (isset($request->app) && !empty($request->app) ? $request->app : []);

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasWarningNotice = GasWarningNotice::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        $saved = 0;
        if($gasWarningNotice->id && isset($appliance[$serial]) && !empty($appliance[$serial])):
            $theAppliance = $appliance[$serial];
            $appliance_location_id = (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null);
            if(!empty($appliance_location_id)):
                $gasAppliance = GasWarningNoticeAppliance::updateOrCreate(['gas_warning_notice_id' => $gasWarningNotice->id, 'appliance_serial' => $serial], [
                    'gas_safety_record_id' => $gasWarningNotice->id,
                    'appliance_serial' => $serial,
                    'appliance_location_id' => (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null),
                    'boiler_brand_id' => (isset($theAppliance['boiler_brand_id']) && !empty($theAppliance['boiler_brand_id']) ? $theAppliance['boiler_brand_id'] : null),
                    'model' => (isset($theAppliance['model']) && !empty($theAppliance['model']) ? $theAppliance['model'] : null),
                    'appliance_type_id' => (isset($theAppliance['appliance_type_id']) && !empty($theAppliance['appliance_type_id']) ? $theAppliance['appliance_type_id'] : null),
                    'serial_no' => (isset($theAppliance['serial_no']) && !empty($theAppliance['serial_no']) ? $theAppliance['serial_no'] : null),
                    'gc_no' => (isset($theAppliance['gc_no']) && !empty($theAppliance['gc_no']) ? $theAppliance['gc_no'] : null),
                    'gas_warning_classification_id' => (isset($theAppliance['gas_warning_classification_id']) && !empty($theAppliance['gas_warning_classification_id']) ? $theAppliance['gas_warning_classification_id'] : null),
                    
                    'gas_escape_issue' => (isset($theAppliance['gas_escape_issue']) && !empty($theAppliance['gas_escape_issue']) ? $theAppliance['gas_escape_issue'] : null),
                    'pipework_issue' => (isset($theAppliance['pipework_issue']) && !empty($theAppliance['pipework_issue']) ? $theAppliance['pipework_issue'] : null),
                    'ventilation_issue' => (isset($theAppliance['ventilation_issue']) && !empty($theAppliance['ventilation_issue']) ? $theAppliance['ventilation_issue'] : null),
                    'meter_issue' => (isset($theAppliance['meter_issue']) && !empty($theAppliance['meter_issue']) ? $theAppliance['meter_issue'] : null),
                    'chimeny_issue' => (isset($theAppliance['chimeny_issue']) && !empty($theAppliance['chimeny_issue']) ? $theAppliance['chimeny_issue'] : null),
                    'fault_details' => (isset($theAppliance['fault_details']) && !empty($theAppliance['fault_details']) ? $theAppliance['fault_details'] : null),
                    'action_taken' => (isset($theAppliance['action_taken']) && !empty($theAppliance['action_taken']) ? $theAppliance['action_taken'] : null),
                    'actions_required' => (isset($theAppliance['actions_required']) && !empty($theAppliance['actions_required']) ? $theAppliance['actions_required'] : null),
                    'reported_to_hse' => (isset($theAppliance['reported_to_hse']) && !empty($theAppliance['reported_to_hse']) ? $theAppliance['reported_to_hse'] : null),
                    'reported_to_hde' => (isset($theAppliance['reported_to_hde']) && !empty($theAppliance['reported_to_hde']) ? $theAppliance['reported_to_hde'] : null),
                    'left_on_premisies' => (isset($theAppliance['left_on_premisies']) && !empty($theAppliance['left_on_premisies']) ? $theAppliance['left_on_premisies'] : null),
                    
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]);
                $saved = 1;
            endif;

            return response()->json(['msg' => 'Appliance '.$serial.' Details successfully updated.', 'saved' => $saved], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try later or contact with the Administrator.'], 422);
        endif;
    }

    public function storeSignatures(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        
        $gasWarningNotice = GasWarningNotice::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
            'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
            'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
            'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        
        if($request->input('sign') !== null):
            $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
            $signatureData = base64_decode($signatureData);
            if(strlen($signatureData) > 2621):
                $gasWarningNotice->deleteSignature();
                
                $imageName = 'signatures/' . Str::uuid() . '.png';
                Storage::disk('public')->put($imageName, $signatureData);
                $signature = new Signature();
                $signature->model_type = GasWarningNotice::class;
                $signature->model_id = $gasWarningNotice->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $imageName;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
            endif;
        endif;

        return response()->json(['msg' => 'Gas Warning Notice Successfully Saved.', 'saved' => 1, 'red' => route('records', [$form->slug, $customer_job_id])], 200);
    }
}
