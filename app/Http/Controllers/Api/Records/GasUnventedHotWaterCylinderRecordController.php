<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasUnventedHotWaterCylinderRecord;
use App\Models\GasUnventedHotWaterCylinderRecordInspection;
use App\Models\GasUnventedHotWaterCylinderRecordSystem;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class GasUnventedHotWaterCylinderRecordController extends Controller
{
    public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasUnventedHotWaterCylinderRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasUnventedHotWaterCylinderRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasUnventedHotWaterCylinderRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request){
        $user_id = $request->user()->id;
        $job_form_id = 17;
        $form = JobForm::find($job_form_id);

        $certificate_id = (isset($request->certificate_id) && $request->certificate_id > 0 ? $request->certificate_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $property = CustomerProperty::find($customer_property_id);
        
        $unventedSystems = $request->unventedSystems;
        $inspectionRecords = $request->inspectionRecords;

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
            $gasUnvenHWCRecord = GasUnventedHotWaterCylinderRecord::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            $this->checkAndUpdateRecordHistory($gasUnvenHWCRecord->id);

            if($gasUnvenHWCRecord->id):
                $gasUnvenHWCRecordSystem = GasUnventedHotWaterCylinderRecordSystem::updateOrCreate(['gas_unvented_hot_water_cylinder_record_id' => $gasUnvenHWCRecord->id], [
                    'gas_unvented_hot_water_cylinder_record_id' => $gasUnvenHWCRecord->id,
                    
                    'type' => (isset($unventedSystems->type) && !empty($unventedSystems->type) ? $unventedSystems->type : null),
                    'make' => (isset($unventedSystems->make) && !empty($unventedSystems->make) ? $unventedSystems->make : null),
                    'model' => (isset($unventedSystems->model) && !empty($unventedSystems->model) ? $unventedSystems->model : null),
                    'location' => (isset($unventedSystems->location) && !empty($unventedSystems->location) ? $unventedSystems->location : null),
                    'serial_no' => (isset($unventedSystems->serial_no) && !empty($unventedSystems->serial_no) ? $unventedSystems->serial_no : null),
                    'gc_number' => (isset($unventedSystems->gc_number) && !empty($unventedSystems->gc_number) ? $unventedSystems->gc_number : null),
                    'direct_or_indirect' => (isset($unventedSystems->direct_or_indirect) && !empty($unventedSystems->direct_or_indirect) ? $unventedSystems->direct_or_indirect : null),
                    'boiler_solar_immersion' => (isset($unventedSystems->boiler_solar_immersion) && !empty($unventedSystems->boiler_solar_immersion) ? $unventedSystems->boiler_solar_immersion : null),
                    'capacity' => (isset($unventedSystems->capacity) && !empty($unventedSystems->capacity) ? $unventedSystems->capacity : null),
                    'warning_label_attached' => (isset($unventedSystems->warning_label_attached) && !empty($unventedSystems->warning_label_attached) ? $unventedSystems->warning_label_attached : null),
                    'water_pressure' => (isset($unventedSystems->water_pressure) && !empty($unventedSystems->water_pressure) ? $unventedSystems->water_pressure : null),
                    'flow_rate' => (isset($unventedSystems->flow_rate) && !empty($unventedSystems->flow_rate) ? $unventedSystems->flow_rate : null),
                    'fully_commissioned' => (isset($unventedSystems->fully_commissioned) && !empty($unventedSystems->fully_commissioned) ? $unventedSystems->fully_commissioned : null),
                    
                    'updated_by' => $user_id,
                ]);
                $gasUnvenHWCRecordInspection = GasUnventedHotWaterCylinderRecordInspection::updateOrCreate(['gas_unvented_hot_water_cylinder_record_id' => $gasUnvenHWCRecord->id], [
                    'gas_unvented_hot_water_cylinder_record_id' => $gasUnvenHWCRecord->id,
                    
                    'system_opt_pressure' => (isset($inspectionRecords->system_opt_pressure) && !empty($inspectionRecords->system_opt_pressure) ? $inspectionRecords->system_opt_pressure : null),
                    'opt_presure_exp_vsl' => (isset($inspectionRecords->opt_presure_exp_vsl) && !empty($inspectionRecords->opt_presure_exp_vsl) ? $inspectionRecords->opt_presure_exp_vsl : null),
                    'opt_presure_exp_vlv' => (isset($inspectionRecords->opt_presure_exp_vlv) && !empty($inspectionRecords->opt_presure_exp_vlv) ? $inspectionRecords->opt_presure_exp_vlv : null),
                    'tem_relief_vlv' => (isset($inspectionRecords->tem_relief_vlv) && !empty($inspectionRecords->tem_relief_vlv) ? $inspectionRecords->tem_relief_vlv : null),
                    'opt_temperature' => (isset($inspectionRecords->opt_temperature) && !empty($inspectionRecords->opt_temperature) ? $inspectionRecords->opt_temperature : null),
                    'combined_temp_presr' => (isset($inspectionRecords->combined_temp_presr) && !empty($inspectionRecords->combined_temp_presr) ? $inspectionRecords->combined_temp_presr : null),
                    'max_circuit_presr' => (isset($inspectionRecords->max_circuit_presr) && !empty($inspectionRecords->max_circuit_presr) ? $inspectionRecords->max_circuit_presr : null),
                    'flow_temp' => (isset($inspectionRecords->flow_temp) && !empty($inspectionRecords->flow_temp) ? $inspectionRecords->flow_temp : null),
                    'd1_mormal_size' => (isset($inspectionRecords->d1_mormal_size) && !empty($inspectionRecords->d1_mormal_size) ? $inspectionRecords->d1_mormal_size : null),
                    'd1_length' => (isset($inspectionRecords->d1_length) && !empty($inspectionRecords->d1_length) ? $inspectionRecords->d1_length : null),
                    'd1_discharges_no' => (isset($inspectionRecords->d1_discharges_no) && !empty($inspectionRecords->d1_discharges_no) ? $inspectionRecords->d1_discharges_no : null),
                    'd1_manifold_size' => (isset($inspectionRecords->d1_manifold_size) && !empty($inspectionRecords->d1_manifold_size) ? $inspectionRecords->d1_manifold_size : null),
                    'd1_is_tundish_install_same_location' => (isset($inspectionRecords->d1_is_tundish_install_same_location) && !empty($inspectionRecords->d1_is_tundish_install_same_location) ? $inspectionRecords->d1_is_tundish_install_same_location : null),
                    'd1_is_tundish_visible' => (isset($inspectionRecords->d1_is_tundish_visible) && !empty($inspectionRecords->d1_is_tundish_visible) ? $inspectionRecords->d1_is_tundish_visible : null),
                    'd1_is_auto_dis_intall' => (isset($inspectionRecords->d1_is_auto_dis_intall) && !empty($inspectionRecords->d1_is_auto_dis_intall) ? $inspectionRecords->d1_is_auto_dis_intall : null),
                    'd2_mormal_size' => (isset($inspectionRecords->d2_mormal_size) && !empty($inspectionRecords->d2_mormal_size) ? $inspectionRecords->d2_mormal_size : null),
                    'd2_pipework_material' => (isset($inspectionRecords->d2_pipework_material) && !empty($inspectionRecords->d2_pipework_material) ? $inspectionRecords->d2_pipework_material : null),
                    'd2_minimum_v_length' => (isset($inspectionRecords->d2_minimum_v_length) && !empty($inspectionRecords->d2_minimum_v_length) ? $inspectionRecords->d2_minimum_v_length : null),
                    'd2_fall_continuously' => (isset($inspectionRecords->d2_fall_continuously) && !empty($inspectionRecords->d2_fall_continuously) ? $inspectionRecords->d2_fall_continuously : null),
                    'd2_termination_method' => (isset($inspectionRecords->d2_termination_method) && !empty($inspectionRecords->d2_termination_method) ? $inspectionRecords->d2_termination_method : null),
                    'd2_termination_satisfactory' => (isset($inspectionRecords->d2_termination_satisfactory) && !empty($inspectionRecords->d2_termination_satisfactory) ? $inspectionRecords->d2_termination_satisfactory : null),
                    'comments' => (isset($inspectionRecords->comments) && !empty($inspectionRecords->comments) ? $inspectionRecords->comments : null),
                    
                    'updated_by' => $user_id,
                ]);
            endif;

            if($request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $gasUnvenHWCRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    $signature = new Signature();
                    $signature->model_type = GasUnventedHotWaterCylinderRecord::class;
                    $signature->model_id = $gasUnvenHWCRecord->id;
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
                'data' => $gasUnvenHWCRecord
                ], 200);
        else:
            return response()->json([
                'message' => 'Something went wrong. Please try again later or contact with the administrator.'
            ], 304);
        endif;
    }

    public function getDetails($record_id){
        try {
            $record = GasUnventedHotWaterCylinderRecord::with(['customer', 'system', 'inspection', 'signature'])->findOrFail($record_id);
          return response()->json([
                'success' => true,
                'data' => $record
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gas unvented hot water cylinder record not found. . The requested Gas unvented hot water cylinder record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 500);
        }
    }
}
