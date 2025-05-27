<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasPowerFlushRecord;
use App\Models\GasPowerFlushRecordChecklist;
use App\Models\GasPowerFlushRecordRediator;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;
use Exception;

class GasPowerFlushRecordController extends Controller
{
     public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasPowerFlushRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasPowerFlushRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasPowerFlushRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request){
        $job_form_id = 15;
        
        $user_id = $request->user()->id;
        $form = JobForm::find($job_form_id);

        $certificate_id = (isset($request->certificate_id) && $request->certificate_id > 0 ? $request->certificate_id : 0);
        $customer_job_id = (isset($request->job_id) && $request->job_id > 0 ? $request->job_id : 0);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);
        $customer_property_id = (isset($request->customer_property_id) && $request->customer_property_id > 0 ? $request->customer_property_id : 0);
        $property = CustomerProperty::find($customer_property_id);
        
        $powerFlushChecklist = $request->powerFlushChecklist;
        $radiators = $request->radiators;

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
            $gasPowerFlush = GasPowerFlushRecord::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            $this->checkAndUpdateRecordHistory($gasPowerFlush->id);

            if($gasPowerFlush->id):
                $gasPowerFlushChecklist = GasPowerFlushRecordChecklist::updateOrCreate(['gas_power_flush_record_id' => $gasPowerFlush->id], [
                    'gas_power_flush_record_id' => $gasPowerFlush->id,
    
                    'powerflush_system_type_id' => (isset($powerFlushChecklist->powerflush_system_type_id) && !empty($powerFlushChecklist->powerflush_system_type_id) ? $powerFlushChecklist->powerflush_system_type_id : null),
                    'boiler_brand_id' => (isset($powerFlushChecklist->boiler_brand_id) && !empty($powerFlushChecklist->boiler_brand_id) ? $powerFlushChecklist->boiler_brand_id : null),
                    'radiators' => (isset($powerFlushChecklist->radiators) && !empty($powerFlushChecklist->radiators) ? $powerFlushChecklist->radiators : null),
                    'pipework' => (isset($powerFlushChecklist->pipework) && !empty($powerFlushChecklist->pipework) ? $powerFlushChecklist->pipework : null),
                    'appliance_type_id' => (isset($powerFlushChecklist->appliance_type_id) && !empty($powerFlushChecklist->appliance_type_id) ? $powerFlushChecklist->appliance_type_id : null),
                    'appliance_location_id' => (isset($powerFlushChecklist->appliance_location_id) && !empty($powerFlushChecklist->appliance_location_id) ? $powerFlushChecklist->appliance_location_id : null),
                    'serial_no' => (isset($powerFlushChecklist->serial_no) && !empty($powerFlushChecklist->serial_no) ? $powerFlushChecklist->serial_no : null),
                    'powerflush_cylinder_type_id' => (isset($powerFlushChecklist->powerflush_cylinder_type_id) && !empty($powerFlushChecklist->powerflush_cylinder_type_id) ? $powerFlushChecklist->powerflush_cylinder_type_id : null),
                    'powerflush_pipework_type_id' => (isset($powerFlushChecklist->powerflush_pipework_type_id) && !empty($powerFlushChecklist->powerflush_pipework_type_id) ? $powerFlushChecklist->powerflush_pipework_type_id : null),
                    'twin_radiator_vlv_fitted' => (isset($powerFlushChecklist->twin_radiator_vlv_fitted) && !empty($powerFlushChecklist->twin_radiator_vlv_fitted) ? $powerFlushChecklist->twin_radiator_vlv_fitted : null),
                    'completely_warm_on_fired' => (isset($powerFlushChecklist->completely_warm_on_fired) && !empty($powerFlushChecklist->completely_warm_on_fired) ? $powerFlushChecklist->completely_warm_on_fired : null),
                    'circulation_for_all_readiators' => (isset($powerFlushChecklist->circulation_for_all_readiators) && !empty($powerFlushChecklist->circulation_for_all_readiators) ? $powerFlushChecklist->circulation_for_all_readiators : null),
                    'suffifiently_sound' => (isset($powerFlushChecklist->suffifiently_sound) && !empty($powerFlushChecklist->suffifiently_sound) ? $powerFlushChecklist->suffifiently_sound : null),
                    'powerflush_circulator_pump_location_id' => (isset($powerFlushChecklist->powerflush_circulator_pump_location_id) && !empty($powerFlushChecklist->powerflush_circulator_pump_location_id) ? $powerFlushChecklist->powerflush_circulator_pump_location_id : null),
                    'number_of_radiators' => (isset($powerFlushChecklist->number_of_radiators) && !empty($powerFlushChecklist->number_of_radiators) ? $powerFlushChecklist->number_of_radiators : null),
                    'radiator_type_id' => (isset($powerFlushChecklist->radiator_type_id) && !empty($powerFlushChecklist->radiator_type_id) ? $powerFlushChecklist->radiator_type_id : null),
                    'getting_warm' => (isset($powerFlushChecklist->getting_warm) && !empty($powerFlushChecklist->getting_warm) ? $powerFlushChecklist->getting_warm : null),
                    'are_trvs_fitted' => (isset($powerFlushChecklist->are_trvs_fitted) && !empty($powerFlushChecklist->are_trvs_fitted) ? $powerFlushChecklist->are_trvs_fitted : null),
                    'sign_of_neglect' => (isset($powerFlushChecklist->sign_of_neglect) && !empty($powerFlushChecklist->sign_of_neglect) ? $powerFlushChecklist->sign_of_neglect : null),
                    'radiator_open_fully' => (isset($powerFlushChecklist->radiator_open_fully) && !empty($powerFlushChecklist->radiator_open_fully) ? $powerFlushChecklist->radiator_open_fully : null),
                    'number_of_valves' => (isset($powerFlushChecklist->number_of_valves) && !empty($powerFlushChecklist->number_of_valves) ? $powerFlushChecklist->number_of_valves : null),
                    'valves_located' => (isset($powerFlushChecklist->valves_located) && !empty($powerFlushChecklist->valves_located) ? $powerFlushChecklist->valves_located : null),
                    'fe_tank_location' => (isset($powerFlushChecklist->fe_tank_location) && !empty($powerFlushChecklist->fe_tank_location) ? $powerFlushChecklist->fe_tank_location : null),
                    'fe_tank_checked' => (isset($powerFlushChecklist->fe_tank_checked) && !empty($powerFlushChecklist->fe_tank_checked) ? $powerFlushChecklist->fe_tank_checked : null),
                    'fe_tank_condition' => (isset($powerFlushChecklist->fe_tank_condition) && !empty($powerFlushChecklist->fe_tank_condition) ? $powerFlushChecklist->fe_tank_condition : null),
                    'color_id' => (isset($powerFlushChecklist->color_id) && !empty($powerFlushChecklist->color_id) ? $powerFlushChecklist->color_id : null),
                    'before_color_id' => (isset($powerFlushChecklist->before_color_id) && !empty($powerFlushChecklist->before_color_id) ? $powerFlushChecklist->before_color_id : null),
                    'mw_ph' => (isset($powerFlushChecklist->mw_ph) && !empty($powerFlushChecklist->mw_ph) ? $powerFlushChecklist->mw_ph : null),
                    'mw_chloride' => (isset($powerFlushChecklist->mw_chloride) && !empty($powerFlushChecklist->mw_chloride) ? $powerFlushChecklist->mw_chloride : null),
                    'mw_hardness' => (isset($powerFlushChecklist->mw_hardness) && !empty($powerFlushChecklist->mw_hardness) ? $powerFlushChecklist->mw_hardness : null),
                    'mw_inhibitor' => (isset($powerFlushChecklist->mw_inhibitor) && !empty($powerFlushChecklist->mw_inhibitor) ? $powerFlushChecklist->mw_inhibitor : null),
                    'bpf_ph' => (isset($powerFlushChecklist->bpf_ph) && !empty($powerFlushChecklist->bpf_ph) ? $powerFlushChecklist->bpf_ph : null),
                    'bpf_chloride' => (isset($powerFlushChecklist->bpf_chloride) && !empty($powerFlushChecklist->bpf_chloride) ? $powerFlushChecklist->bpf_chloride : null),
                    'bpf_hardness' => (isset($powerFlushChecklist->bpf_hardness) && !empty($powerFlushChecklist->bpf_hardness) ? $powerFlushChecklist->bpf_hardness : null),
                    'bpf_inhibitor' => (isset($powerFlushChecklist->bpf_inhibitor) && !empty($powerFlushChecklist->bpf_inhibitor) ? $powerFlushChecklist->bpf_inhibitor : null),
                    'apf_ph' => (isset($powerFlushChecklist->apf_ph) && !empty($powerFlushChecklist->apf_ph) ? $powerFlushChecklist->apf_ph : null),
                    'apf_chloride' => (isset($powerFlushChecklist->apf_chloride) && !empty($powerFlushChecklist->apf_chloride) ? $powerFlushChecklist->apf_chloride : null),
                    'apf_hardness' => (isset($powerFlushChecklist->apf_hardness) && !empty($powerFlushChecklist->apf_hardness) ? $powerFlushChecklist->apf_hardness : null),
                    'apf_inhibitor' => (isset($powerFlushChecklist->apf_inhibitor) && !empty($powerFlushChecklist->apf_inhibitor) ? $powerFlushChecklist->apf_inhibitor : null),
                    'mw_tds_reading' => (isset($powerFlushChecklist->mw_tds_reading) && !empty($powerFlushChecklist->mw_tds_reading) ? $powerFlushChecklist->mw_tds_reading : null),
                    'bf_tds_reading' => (isset($powerFlushChecklist->bf_tds_reading) && !empty($powerFlushChecklist->bf_tds_reading) ? $powerFlushChecklist->bf_tds_reading : null),
                    'af_tds_reading' => (isset($powerFlushChecklist->af_tds_reading) && !empty($powerFlushChecklist->af_tds_reading) ? $powerFlushChecklist->af_tds_reading : null),
    
                    'updated_by' => $user_id,
                ]);

                $appliance_serial = (isset($radiators->appliance_serial) && $radiators->appliance_serial > 0 ? $radiators->appliance_serial : 1);
                GasPowerFlushRecordRediator::where('gas_power_flush_record_id', $gasPowerFlush->id)->forceDelete();
                if(!empty($radiators)):
                    foreach($radiators as $serial => $radiator):
                        $gasPowerFlushRadiator = GasPowerFlushRecordRediator::create([
                            'gas_power_flush_record_id' => $gasPowerFlush->id,
        
                            'rediator_location' => (isset($radiator->rediator_location) && !empty($radiator->rediator_location) ? $radiator->rediator_location : null),
                            'tmp_b_top' => (isset($radiator->tmp_b_top) && !empty($radiator->tmp_b_top) ? $radiator->tmp_b_top : null),
                            'tmp_b_bottom' => (isset($radiator->tmp_b_bottom) && !empty($radiator->tmp_b_bottom) ? $radiator->tmp_b_bottom : null),
                            'tmp_b_left' => (isset($radiator->tmp_b_left) && !empty($radiator->tmp_b_left) ? $radiator->tmp_b_left : null),
                            'tmp_b_right' => (isset($radiator->tmp_b_right) && !empty($radiator->tmp_b_right) ? $radiator->tmp_b_right : null),
                            'tmp_a_top' => (isset($radiator->tmp_a_top) && !empty($radiator->tmp_a_top) ? $radiator->tmp_a_top : null),
                            'tmp_a_bottom' => (isset($radiator->tmp_a_bottom) && !empty($radiator->tmp_a_bottom) ? $radiator->tmp_a_bottom : null),
                            'tmp_a_left' => (isset($radiator->tmp_a_left) && !empty($radiator->tmp_a_left) ? $radiator->tmp_a_left : null),
                            'tmp_a_right' => (isset($radiator->tmp_a_right) && !empty($radiator->tmp_a_right) ? $radiator->tmp_a_right : null)
                        ]);
                    endforeach;
                endif;
            endif;

            if($request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $gasPowerFlush->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    $signature = new Signature();
                    $signature->model_type = GasPowerFlushRecord::class;
                    $signature->model_id = $gasPowerFlush->id;
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
            return response()->json(['message' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function getDetails($record_id){
        try {
            $powerFlush = GasPowerFlushRecord::with(['customer', 'powerflush_checklist', 'powerflush_rediators'])->findOrFail($record_id);
          return response()->json([
                'success' => true,
                'data' => $powerFlush
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Power flush record not found. . The requested Power flush record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 500);
        }
    }
}
