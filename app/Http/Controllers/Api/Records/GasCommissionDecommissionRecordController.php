<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasCommissionDecommissionRecord;
use App\Models\GasCommissionDecommissionRecordAppliance;
use App\Models\GasCommissionDecommissionRecordApplianceWorkType;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Signature;
use Exception;

class GasCommissionDecommissionRecordController extends Controller
{

    public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasCommissionDecommissionRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasCommissionDecommissionRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasCommissionDecommissionRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request){
        $job_form_id = 16;
        
        $user_id = $request->user()->id;
        $form = JobForm::find($job_form_id);

        $certificate_id = (isset($request->certificate_id) && $request->certificate_id > 0 ? $request->certificate_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $property = CustomerProperty::find($customer_property_id);
        
        $appliances = $request->appliances;

        if($customer_job_id == 0):
            $customerJob = CustomerJob::create([
                'customer_id' => $customer_id,
                'customer_property_id' => $customer_property_id,
                'description' => $form->name,
                'details' => 'Job created for '.$property->full_address,

                'created_by' => $request->user()->id
            ]);
            $customer_job_id = ($customerJob->id ? $customerJob->id : $customer_job_id);
        endif;

        if($customer_job_id > 0):
            $gasComDecRecord = GasCommissionDecommissionRecord::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            $this->checkAndUpdateRecordHistory($gasComDecRecord->id);

            if(!empty($appliances) && $gasComDecRecord->id):
                $existAppliances = GasCommissionDecommissionRecordAppliance::where('gas_commission_decommission_record_id', $gasComDecRecord->id)->pluck('id')->unique()->toArray();
                if(!empty($existAppliances)):
                    GasCommissionDecommissionRecordApplianceWorkType::whereIn('gas_commission_decommission_record_appliance_id', $existAppliances)->forceDelete();
                endif;
                $workTypes = (isset($appliances->work_type) && !empty($appliances->work_type) ? $appliances->work_type : []);

                $appliance_serial = (isset($appliances->appliance_serial) && $appliances->appliance_serial > 0 ? $appliances->appliance_serial : 1);
                $gasComDecRecAppliance = GasCommissionDecommissionRecordAppliance::updateOrCreate(['gas_commission_decommission_record_id' => $gasComDecRecord->id, 'appliance_serial' => $appliance_serial], [
                    'gas_commission_decommission_record_id' => $gasComDecRecord->id,
                    'appliance_serial' => $appliance_serial,
                    
                    'details_work_carried_out' => (isset($appliances->details_work_carried_out) && !empty($appliances->details_work_carried_out) ? $appliances->details_work_carried_out : null),
                    'details_work_required' => (isset($appliances->details_work_required) && !empty($appliances->details_work_required) ? $appliances->details_work_required : null),
                    'is_safe_to_use' => (isset($appliances->is_safe_to_use) && !empty($appliances->is_safe_to_use) ? $appliances->is_safe_to_use : null),
                    'have_labels_affixed' => (isset($appliances->have_labels_affixed) && !empty($appliances->have_labels_affixed) ? $appliances->have_labels_affixed : null),
                    
                    'updated_by' => $user_id,
                ]);
                if($gasComDecRecAppliance->id):
                    foreach($workTypes as $wt):
                        $theType = GasCommissionDecommissionRecordApplianceWorkType::create([
                            'gas_commission_decommission_record_appliance_id' => $gasComDecRecAppliance->id,
                            'commission_decommission_work_type_id' => $wt
                        ]);
                    endforeach;
                endif;
            endif;

            if($request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $gasComDecRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    $signature = new Signature();
                    $signature->model_type = GasCommissionDecommissionRecord::class;
                    $signature->model_id = $gasComDecRecord->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([request()->ip()]);
                    $signature->save();
                endif;
            endif;

            return response()->json([
                'success' => true,
                'message' => 'Certificate successfully created.',
                ], 200);
        else:
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator.'
            ], 304);
        endif;
    }

    public function getDetails($record_id){
        try {
            $commission_decommision = GasCommissionDecommissionRecord::with(['customer', 'appliance', 'signature'])->findOrFail($record_id);
          return response()->json([
                'success' => true,
                'data' => $commission_decommision
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Commission/decommission record not found. The requested record (ID: ' . $record_id . ') does not exist or may have been deleted.',
            ], 500);
        }
    }
}
