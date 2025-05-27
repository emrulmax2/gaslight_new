<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasLandlordSafetyRecord;
use App\Models\GasLandlordSafetyRecordAppliance;
use App\Models\GasSafetyRecord;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Support\Facades\Storage;

class LandlordGasSafetyController extends Controller
{

     public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasLandlordSafetyRecord::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasLandlordSafetyRecord::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasLandlordSafetyRecord::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }


    public function store(Request $request)
    {
        try {
            $job_form_id = 7;

            $user_id = $request->user()->id;
            $form = JobForm::findOrFail($job_form_id);

            $certificate_id = $request->certificate_id ?? 0;
            $customer_job_id = $request->job_id ?? 0;
            $customer_id = $request->customer_id;
            $customer_property_id = $request->customer_property_id;
            $property = CustomerProperty::findOrFail($customer_property_id);
            
            $appliances = $request->appliances;
            $safetyChecks = $request->safetyChecks;
            $glsrComments = $request->glsrComments;

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

            $gasSafetyRecord = GasLandlordSafetyRecord::updateOrCreate(
                [
                    'id' => $certificate_id, 
                    'customer_job_id' => $customer_job_id, 
                    'job_form_id' => $job_form_id
                ], 
                [
                    'customer_id' => $customer_id,
                    'customer_job_id' => $customer_job_id,
                    'job_form_id' => $job_form_id,
                    'satisfactory_visual_inspaction' => $safetyChecks->satisfactory_visual_inspaction ?? null,
                    'emergency_control_accessible' => $safetyChecks->emergency_control_accessible ?? null,
                    'satisfactory_gas_tightness_test' => $safetyChecks->satisfactory_gas_tightness_test ?? null,
                    'equipotential_bonding_satisfactory' => $safetyChecks->equipotential_bonding_satisfactory ?? null,
                    'co_alarm_fitted' => $safetyChecks->co_alarm_fitted ?? null,
                    'co_alarm_in_date' => $safetyChecks->co_alarm_in_date ?? null,
                    'co_alarm_test_satisfactory' => $safetyChecks->co_alarm_test_satisfactory ?? null,
                    'smoke_alarm_fitted' => $safetyChecks->smoke_alarm_fitted ?? null,
                    'fault_details' => $glsrComments->fault_details ?? null,
                    'rectification_work_carried_out' => $glsrComments->rectification_work_carried_out ?? null,
                    'details_work_carried_out' => $glsrComments->details_work_carried_out ?? null,
                    'flue_cap_put_back' => $glsrComments->flue_cap_put_back ?? null,
                    'inspection_date' => $request->inspection_date ? date('Y-m-d', strtotime($request->inspection_date)) : null,
                    'next_inspection_date' => $request->next_inspection_date ? date('Y-m-d', strtotime($request->next_inspection_date)) : null,
                    'received_by' => $request->received_by ?? null,
                    'relation_id' => $request->relation_id ?? null,
                    'updated_by' => $user_id,
                ]
            );

            $this->checkAndUpdateRecordHistory($gasSafetyRecord->id);

            if (!empty($appliances)) {
                foreach ($appliances as $serial => $appliance) {
                    GasLandlordSafetyRecordAppliance::updateOrCreate(
                        [
                            'gas_landlord_safety_record_id' => $gasSafetyRecord->id, 
                            'appliance_serial' => $serial
                        ],
                        [
                            'gas_landlord_safety_record_id' => $gasSafetyRecord->id,
                            'appliance_serial' => $serial,
                            'appliance_location_id' => $appliance->appliance_location_id ?? null,
                            'boiler_brand_id' => $appliance->boiler_brand_id ?? null,
                            'model' => $appliance->model ?? null,
                            'appliance_type_id' => $appliance->appliance_type_id ?? null,
                            'serial_no' => $appliance->serial_no ?? null,
                            'gc_no' => $appliance->gc_no ?? null,
                            'appliance_flue_type_id' => $appliance->appliance_flue_type_id ?? null,
                            'opt_pressure' => $appliance->opt_pressure ?? null,
                            'safety_devices' => $appliance->safety_devices ?? null,
                            'spillage_test' => $appliance->spillage_test ?? null,
                            'smoke_pellet_test' => $appliance->smoke_pellet_test ?? null,
                            'low_analyser_ratio' => $appliance->low_analyser_ratio ?? null,
                            'low_co' => $appliance->low_co ?? null,
                            'low_co2' => $appliance->low_co2 ?? null,
                            'high_analyser_ratio' => $appliance->high_analyser_ratio ?? null,
                            'high_co' => $appliance->high_co ?? null,
                            'high_co2' => $appliance->high_co2 ?? null,
                            'satisfactory_termination' => $appliance->satisfactory_termination ?? null,
                            'flue_visual_condition' => $appliance->flue_visual_condition ?? null,
                            'adequate_ventilation' => $appliance->adequate_ventilation ?? null,
                            'landlord_appliance' => $appliance->landlord_appliance ?? null,
                            'inspected' => $appliance->inspected ?? null,
                            'appliance_visual_check' => $appliance->appliance_visual_check ?? null,
                            'appliance_serviced' => $appliance->appliance_serviced ?? null,
                            'appliance_safe_to_use' => $appliance->appliance_safe_to_use ?? null,
                            'updated_by' => $user_id,
                        ]
                    );
                }
            }

            if ($request->input('sign') !== null) {
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                
                if (strlen($signatureData) > 2621) {
                    $gasSafetyRecord->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    
                    $signature = new Signature();
                    $signature->model_type = GasLandlordSafetyRecord::class;
                    $signature->model_id = $gasSafetyRecord->id;
                    $signature->uuid = Str::uuid();
                    $signature->filename = $imageName;
                    $signature->document_filename = null;
                    $signature->certified = false;
                    $signature->from_ips = json_encode([request()->ip()]);
                    $signature->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Certificate successfully created/updated',
                'data' => [
                    'record_id' => $gasSafetyRecord->id,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDetails($gas_safety_id)
    {
        try {
            $gasSafetyRecord = GasLandlordSafetyRecord::with(['customer','appliance','signature'])->findOrFail($gas_safety_id);

            return response()->json([
                'success' => true,
                'data' => $gasSafetyRecord
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Landlord safety record not found. . The requested Landlord safety record (ID: '.$gas_safety_id.') does not exist or may have been deleted.',
            ], 500);
        }
    }
}
