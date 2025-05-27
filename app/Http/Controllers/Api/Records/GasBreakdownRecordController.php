<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasBreakdownRecord;
use App\Models\GasBreakdownRecordAppliance;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Signature;
use Exception;

class GasBreakdownRecordController extends Controller
{
     public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasBreakdownRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasBreakdownRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasBreakdownRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request)
    {
        try {
            $job_form_id = 10;

            $user_id = $request->user()->id;
            $form = JobForm::findOrFail($job_form_id);

            $certificate_id = $request->certificate_id ?? 0;
            $customer_job_id = $request->job_id ?? 0;
            $customer_id = $request->customer_id;
            $customer_property_id = $request->customer_property_id;
            $property = CustomerProperty::findOrFail($customer_property_id);
            
            $appliances = $request->appliances;

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

            $gasBreakdownRecord = GasBreakdownRecord::updateOrCreate(
                [
                    'id' => $certificate_id,
                    'customer_job_id' => $customer_job_id,
                    'job_form_id' => $job_form_id
                ],
                [
                    'customer_id' => $customer_id,
                    'customer_job_id' => $customer_job_id,
                    'job_form_id' => $job_form_id,
                    'inspection_date' => $request->inspection_date ? date('Y-m-d', strtotime($request->inspection_date)) : null,
                    'next_inspection_date' => $request->next_inspection_date ? date('Y-m-d', strtotime($request->next_inspection_date)) : null,
                    'received_by' => $request->received_by,
                    'relation_id' => $request->relation_id,
                    'updated_by' => $user_id,
                ]
            );

            $this->checkAndUpdateRecordHistory($gasBreakdownRecord->id);

            if (!empty($appliances) && $gasBreakdownRecord->id) {
                $appliance_serial = $appliances->appliance_serial ?? 1;
                
                GasBreakdownRecordAppliance::updateOrCreate(
                    [
                        'gas_breakdown_record_id' => $gasBreakdownRecord->id,
                        'appliance_serial' => $appliance_serial
                    ],
                    [
                       'gas_breakdown_record_id' => $gasBreakdownRecord->id,
                    'appliance_serial' => $appliance_serial,
                    
                    'appliance_location_id' => (isset($appliances->appliance_location_id) && !empty($appliances->appliance_location_id) ? $appliances->appliance_location_id : null),
                    'boiler_brand_id' => (isset($appliances->boiler_brand_id) && !empty($appliances->boiler_brand_id) ? $appliances->boiler_brand_id : null),
                    'model' => (isset($appliances->model) && !empty($appliances->model) ? $appliances->model : null),
                    'appliance_type_id' => (isset($appliances->appliance_type_id) && !empty($appliances->appliance_type_id) ? $appliances->appliance_type_id : null),
                    'serial_no' => (isset($appliances->serial_no) && !empty($appliances->serial_no) ? $appliances->serial_no : null),
                    'gc_no' => (isset($appliances->gc_no) && !empty($appliances->gc_no) ? $appliances->gc_no : null),
                    
                    'performance_analyser_ratio' => (isset($appliances->performance_analyser_ratio) && !empty($appliances->performance_analyser_ratio) ? $appliances->performance_analyser_ratio : null),
                    'performance_co' => (isset($appliances->performance_co) && !empty($appliances->performance_co) ? $appliances->performance_co : null),
                    'performance_co2' => (isset($appliances->performance_co2) && !empty($appliances->performance_co2) ? $appliances->performance_co2 : null),
                    'opt_correctly' => (isset($appliances->opt_correctly) && !empty($appliances->opt_correctly) ? $appliances->opt_correctly : null),
                    'conf_safety_standards' => (isset($appliances->conf_safety_standards) && !empty($appliances->conf_safety_standards) ? $appliances->conf_safety_standards : null),
                    'notice_exlained' => (isset($appliances->notice_exlained) && !empty($appliances->notice_exlained) ? $appliances->notice_exlained : null),
                    'flueing_is_safe' => (isset($appliances->flueing_is_safe) && !empty($appliances->flueing_is_safe) ? $appliances->flueing_is_safe : null),
                    'ventilation_is_safe' => (isset($appliances->ventilation_is_safe) && !empty($appliances->ventilation_is_safe) ? $appliances->ventilation_is_safe : null),
                    'emition_combustion_test' => (isset($appliances->emition_combustion_test) && !empty($appliances->emition_combustion_test) ? $appliances->emition_combustion_test : null),
                    'burner_pressure' => (isset($appliances->burner_pressure) && !empty($appliances->burner_pressure) ? $appliances->burner_pressure : null),
                    'location_of_fault' => (isset($appliances->location_of_fault) && !empty($appliances->location_of_fault) ? $appliances->location_of_fault : null),
                    'fault_resolved' => (isset($appliances->fault_resolved) && !empty($appliances->fault_resolved) ? $appliances->fault_resolved : null),
                    'parts_fitted' => (isset($appliances->parts_fitted) && !empty($appliances->parts_fitted) ? $appliances->parts_fitted : null),
                    'fitted_parts_name' => (isset($appliances->fitted_parts_name) && !empty($appliances->fitted_parts_name) ? $appliances->fitted_parts_name : null),
                    'parts_required' => (isset($appliances->parts_required) && !empty($appliances->parts_required) ? $appliances->parts_required : null),
                    'required_parts_name' => (isset($appliances->required_parts_name) && !empty($appliances->required_parts_name) ? $appliances->required_parts_name : null),
                    'monoxide_alarm_fitted' => (isset($appliances->monoxide_alarm_fitted) && !empty($appliances->monoxide_alarm_fitted) ? $appliances->monoxide_alarm_fitted : null),
                    'is_safe' => (isset($appliances->is_safe) && !empty($appliances->is_safe) ? $appliances->is_safe : null),
                    'parts_available' => (isset($appliances->parts_available) && !empty($appliances->parts_available) ? $appliances->parts_available : null),
                    'recommend_replacement' => (isset($appliances->recommend_replacement) && !empty($appliances->recommend_replacement) ? $appliances->recommend_replacement : null),
                    'magnetic_filter_fitted' => (isset($appliances->magnetic_filter_fitted) && !empty($appliances->magnetic_filter_fitted) ? $appliances->magnetic_filter_fitted : null),
                    'improvement_recommended' => (isset($appliances->improvement_recommended) && !empty($appliances->improvement_recommended) ? $appliances->improvement_recommended : null),
                    'enginner_comments' => (isset($appliances->enginner_comments) && !empty($appliances->enginner_comments) ? $appliances->enginner_comments : null),
                    
                    'updated_by' => $user_id,
                    ]
                );
            }

            if ($request->has('sign')) {
                $signatureData = str_replace('data:image/png;base64,', '', $request->sign);
                $signatureData = base64_decode($signatureData);
                
                if (strlen($signatureData) > 2621) {
                    $gasBreakdownRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    
                    $signature = new Signature();
                    $signature->model_type = GasBreakdownRecord::class;
                    $signature->model_id = $gasBreakdownRecord->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([$request->ip()]);
                    $signature->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Gas breakdown record successfully created/updated',
                'data' => [
                    'id' => $gasBreakdownRecord->id,
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function getDetails($record_id){
        try {
            $gasBreakdownRecord = GasBreakdownRecord::with(['customer', 'appliance', 'signature'])->findOrFail($record_id);
          return response()->json([
                'success' => true,
                'data' => $gasBreakdownRecord
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gas safety record not found. The requested record (ID: ' . $record_id . ') does not exist or may have been deleted.',
            ], 500);
        }
    }
}
