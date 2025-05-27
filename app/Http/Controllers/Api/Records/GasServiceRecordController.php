<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasServiceRecord;
use App\Models\GasServiceRecordAppliance;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;
use Exception;

class GasServiceRecordController extends Controller
{
     public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasServiceRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasServiceRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasServiceRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request)
    {
        try {
            $job_form_id = 9;
            
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
            $gasServiceRecord = GasServiceRecord::updateOrCreate(
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

            $this->checkAndUpdateRecordHistory($gasServiceRecord->id);

            if (!empty($appliances) && $gasServiceRecord->id) {
                $appliance_serial = $appliances->appliance_serial ?? 1;
                
                GasServiceRecordAppliance::updateOrCreate(
                    [
                        'gas_service_record_id' => $gasServiceRecord->id,
                        'appliance_serial' => $appliance_serial
                    ],
                    [
                       'gas_service_record_id' => $gasServiceRecord->id,
                    'appliance_serial' => $appliance_serial,
                    
                    'appliance_location_id' => (isset($appliances->appliance_location_id) && !empty($appliances->appliance_location_id) ? $appliances->appliance_location_id : null),
                    'boiler_brand_id' => (isset($appliances->boiler_brand_id) && !empty($appliances->boiler_brand_id) ? $appliances->boiler_brand_id : null),
                    'model' => (isset($appliances->model) && !empty($appliances->model) ? $appliances->model : null),
                    'appliance_type_id' => (isset($appliances->appliance_type_id) && !empty($appliances->appliance_type_id) ? $appliances->appliance_type_id : null),
                    'serial_no' => (isset($appliances->serial_no) && !empty($appliances->serial_no) ? $appliances->serial_no : null),
                    'gc_no' => (isset($appliances->gc_no) && !empty($appliances->gc_no) ? $appliances->gc_no : null),
                    
                    'opt_pressure' => (isset($appliances->opt_pressure) && !empty($appliances->opt_pressure) ? $appliances->opt_pressure : null),
                    'rented_accommodation' => (isset($appliances->rented_accommodation) && !empty($appliances->rented_accommodation) ? $appliances->rented_accommodation : null),
                    'type_of_work_carried_out' => (isset($appliances->type_of_work_carried_out) && !empty($appliances->type_of_work_carried_out) ? $appliances->type_of_work_carried_out : null),
                    'test_carried_out' => (isset($appliances->test_carried_out) && !empty($appliances->test_carried_out) ? $appliances->test_carried_out : null),
                    'is_electricial_bonding' => (isset($appliances->is_electricial_bonding) && !empty($appliances->is_electricial_bonding) ? $appliances->is_electricial_bonding : null),
                    'low_analyser_ratio' => (isset($appliances->low_analyser_ratio) && !empty($appliances->low_analyser_ratio) ? $appliances->low_analyser_ratio : null),
                    'low_co' => (isset($appliances->low_co) && !empty($appliances->low_co) ? $appliances->low_co : null),
                    'low_co2' => (isset($appliances->low_co2) && !empty($appliances->low_co2) ? $appliances->low_co2 : null),
                    'high_analyser_ratio' => (isset($appliances->high_analyser_ratio) && !empty($appliances->high_analyser_ratio) ? $appliances->high_analyser_ratio : null),
                    'high_co' => (isset($appliances->high_co) && !empty($appliances->high_co) ? $appliances->high_co : null),
                    'high_co2' => (isset($appliances->high_co2) && !empty($appliances->high_co2) ? $appliances->high_co2 : null),

                    'heat_exchanger' => (isset($appliances->heat_exchanger) && !empty($appliances->heat_exchanger) ? $appliances->heat_exchanger : null),
                    'heat_exchanger_detail' => (isset($appliances->heat_exchanger_detail) && !empty($appliances->heat_exchanger_detail) ? $appliances->heat_exchanger_detail : null),
                    'burner_injectors' => (isset($appliances->burner_injectors) && !empty($appliances->burner_injectors) ? $appliances->burner_injectors : null),
                    'burner_injectors_detail' => (isset($appliances->burner_injectors_detail) && !empty($appliances->burner_injectors_detail) ? $appliances->burner_injectors_detail : null),
                    'flame_picture' => (isset($appliances->flame_picture) && !empty($appliances->flame_picture) ? $appliances->flame_picture : null),
                    'flame_picture_detail' => (isset($appliances->flame_picture_detail) && !empty($appliances->flame_picture_detail) ? $appliances->flame_picture_detail : null),
                    'ignition' => (isset($appliances->ignition) && !empty($appliances->ignition) ? $appliances->ignition : null),
                    'ignition_detail' => (isset($appliances->ignition_detail) && !empty($appliances->ignition_detail) ? $appliances->ignition_detail : null),
                    'electrics' => (isset($appliances->electrics) && !empty($appliances->electrics) ? $appliances->electrics : null),
                    'electrics_detail' => (isset($appliances->electrics_detail) && !empty($appliances->electrics_detail) ? $appliances->electrics_detail : null),
                    'controls' => (isset($appliances->controls) && !empty($appliances->controls) ? $appliances->controls : null),
                    'controls_detail' => (isset($appliances->controls_detail) && !empty($appliances->controls_detail) ? $appliances->controls_detail : null),
                    'leak_gas_water' => (isset($appliances->leak_gas_water) && !empty($appliances->leak_gas_water) ? $appliances->leak_gas_water : null),
                    'leak_gas_water_detail' => (isset($appliances->leak_gas_water_detail) && !empty($appliances->leak_gas_water_detail) ? $appliances->leak_gas_water_detail : null),
                    'seals' => (isset($appliances->seals) && !empty($appliances->seals) ? $appliances->seals : null),
                    'seals_detail' => (isset($appliances->seals_detail) && !empty($appliances->seals_detail) ? $appliances->seals_detail : null),
                    'pipework' => (isset($appliances->pipework) && !empty($appliances->pipework) ? $appliances->pipework : null),
                    'pipework_detail' => (isset($appliances->pipework_detail) && !empty($appliances->pipework_detail) ? $appliances->pipework_detail : null),
                    'fans' => (isset($appliances->fans) && !empty($appliances->fans) ? $appliances->fans : null),
                    'fans_detail' => (isset($appliances->fans_detail) && !empty($appliances->fans_detail) ? $appliances->fans_detail : null),
                    'fireplace' => (isset($appliances->fireplace) && !empty($appliances->fireplace) ? $appliances->fireplace : null),
                    'fireplace_detail' => (isset($appliances->fireplace_detail) && !empty($appliances->fireplace_detail) ? $appliances->fireplace_detail : null),
                    'closure_plate' => (isset($appliances->closure_plate) && !empty($appliances->closure_plate) ? $appliances->closure_plate : null),
                    'closure_plate_detail' => (isset($appliances->closure_plate_detail) && !empty($appliances->closure_plate_detail) ? $appliances->closure_plate_detail : null),
                    'allowable_location' => (isset($appliances->allowable_location) && !empty($appliances->allowable_location) ? $appliances->allowable_location : null),
                    'allowable_location_detail' => (isset($appliances->allowable_location_detail) && !empty($appliances->allowable_location_detail) ? $appliances->allowable_location_detail : null),
                    'boiler_ratio' => (isset($appliances->boiler_ratio) && !empty($appliances->boiler_ratio) ? $appliances->boiler_ratio : null),
                    'boiler_ratio_detail' => (isset($appliances->boiler_ratio_detail) && !empty($appliances->boiler_ratio_detail) ? $appliances->boiler_ratio_detail : null),
                    'stability' => (isset($appliances->stability) && !empty($appliances->stability) ? $appliances->stability : null),
                    'stability_detail' => (isset($appliances->stability_detail) && !empty($appliances->stability_detail) ? $appliances->stability_detail : null),
                    'return_air_ple' => (isset($appliances->return_air_ple) && !empty($appliances->return_air_ple) ? $appliances->return_air_ple : null),
                    'return_air_ple_detail' => (isset($appliances->return_air_ple_detail) && !empty($appliances->return_air_ple_detail) ? $appliances->return_air_ple_detail : null),
                    'ventillation' => (isset($appliances->ventillation) && !empty($appliances->ventillation) ? $appliances->ventillation : null),
                    'ventillation_detail' => (isset($appliances->ventillation_detail) && !empty($appliances->ventillation_detail) ? $appliances->ventillation_detail : null),
                    'flue_termination' => (isset($appliances->flue_termination) && !empty($appliances->flue_termination) ? $appliances->flue_termination : null),
                    'flue_termination_detail' => (isset($appliances->flue_termination_detail) && !empty($appliances->flue_termination_detail) ? $appliances->flue_termination_detail : null),
                    'smoke_pellet_flue_flow' => (isset($appliances->smoke_pellet_flue_flow) && !empty($appliances->smoke_pellet_flue_flow) ? $appliances->smoke_pellet_flue_flow : null),
                    'smoke_pellet_flue_flow_detail' => (isset($appliances->smoke_pellet_flue_flow_detail) && !empty($appliances->smoke_pellet_flue_flow_detail) ? $appliances->smoke_pellet_flue_flow_detail : null),
                    'smoke_pellet_spillage' => (isset($appliances->smoke_pellet_spillage) && !empty($appliances->smoke_pellet_spillage) ? $appliances->smoke_pellet_spillage : null),
                    'smoke_pellet_spillage_detail' => (isset($appliances->smoke_pellet_spillage_detail) && !empty($appliances->smoke_pellet_spillage_detail) ? $appliances->smoke_pellet_spillage_detail : null),
                    'working_pressure' => (isset($appliances->working_pressure) && !empty($appliances->working_pressure) ? $appliances->working_pressure : null),
                    'working_pressure_detail' => (isset($appliances->working_pressure_detail) && !empty($appliances->working_pressure_detail) ? $appliances->working_pressure_detail : null),
                    'savety_devices' => (isset($appliances->savety_devices) && !empty($appliances->savety_devices) ? $appliances->savety_devices : null),
                    'savety_devices_detail' => (isset($appliances->savety_devices_detail) && !empty($appliances->savety_devices_detail) ? $appliances->savety_devices_detail : null),
                    'gas_tightness' => (isset($appliances->gas_tightness) && !empty($appliances->gas_tightness) ? $appliances->gas_tightness : null),
                    'gas_tightness_detail' => (isset($appliances->gas_tightness_detail) && !empty($appliances->gas_tightness_detail) ? $appliances->gas_tightness_detail : null),
                    'expansion_vassel_checked' => (isset($appliances->expansion_vassel_checked) && !empty($appliances->expansion_vassel_checked) ? $appliances->expansion_vassel_checked : null),
                    'expansion_vassel_checked_detail' => (isset($appliances->expansion_vassel_checked_detail) && !empty($appliances->expansion_vassel_checked_detail) ? $appliances->expansion_vassel_checked_detail : null),
                    'other_regulations' => (isset($appliances->other_regulations) && !empty($appliances->other_regulations) ? $appliances->other_regulations : null),
                    'other_regulations_detail' => (isset($appliances->other_regulations_detail) && !empty($appliances->other_regulations_detail) ? $appliances->other_regulations_detail : null),
                    'is_safe_to_use' => (isset($appliances->is_safe_to_use) && !empty($appliances->is_safe_to_use) ? $appliances->is_safe_to_use : null),
                    'instruction_followed' => (isset($appliances->instruction_followed) && !empty($appliances->instruction_followed) ? $appliances->instruction_followed : null),
                    'work_required_note' => (isset($appliances->work_required_note) && !empty($appliances->work_required_note) ? $appliances->work_required_note : null),
                    
                    'updated_by' => $user_id,
                    ]
                );
            }

            if ($request->has('sign')) {
                $signatureData = str_replace('data:image/png;base64,', '', $request->sign);
                $signatureData = base64_decode($signatureData);
                
                if (strlen($signatureData) > 2621) {
                    $gasServiceRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    
                    $signature = new Signature();
                    $signature->model_type = GasServiceRecord::class;
                    $signature->model_id = $gasServiceRecord->id;
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
                'message' => 'Gas service record successfully created/updated',
                'data' => [
                    'id' => $gasServiceRecord->id,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function getDetails($record_id){
        try {
            $gasServiceRecord = GasServiceRecord::with(['customer', 'appliances', 'signature'])->findOrFail($record_id);
          return response()->json([
                'success' => true,
                'data' => $gasServiceRecord
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gas service record not found. . The requested Gas service record (ID: '.$record_id.') does not exist or may have been deleted.',
            ], 500);
        }
    }
}
