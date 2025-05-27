<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\GasBoilerSystemCommissioningChecklist;
use App\Models\GasBoilerSystemCommissioningChecklistAppliance;
use App\Models\GasCommissionDecommissionRecord;
use App\Models\JobForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Signature;
use Exception;
use Illuminate\Support\Str;

class GasBoilerSystemCommissioningChecklistController extends Controller
{

     public function checkAndUpdateRecordHistory($record_id){ 
        $record = GasBoilerSystemCommissioningChecklist::find($record_id);
        $existingRD = ExistingRecordDraft::updateOrCreate([ 'model_type' => GasBoilerSystemCommissioningChecklist::class, 'model_id' => $record_id ], [
            'customer_id' => $record->customer_id,
            'customer_job_id' => $record->customer_job_id,
            'job_form_id' => $record->job_form_id,
            'model_type' => GasBoilerSystemCommissioningChecklist::class,
            'model_id' => $record->id,

            'created_by' => $record->created_by,
            'updated_by' => request()->user()->id,
        ]);
    }

    public function store(Request $request){
        $job_form_id = 13;
        
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
            $gasBoilerSCC = GasBoilerSystemCommissioningChecklist::updateOrCreate(['id' => $certificate_id, 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
                'customer_id' => $customer_id,
                'customer_job_id' => $customer_job_id,
                'job_form_id' => $job_form_id,

                'inspection_date' => (isset($request->inspection_date) && !empty($request->inspection_date) ? date('Y-m-d', strtotime($request->inspection_date)) : null),
                'next_inspection_date' => (isset($request->next_inspection_date) && !empty($request->next_inspection_date) ? date('Y-m-d', strtotime($request->next_inspection_date)) : null),
                'received_by' => (isset($request->received_by) && !empty($request->received_by) ? $request->received_by : null),
                'relation_id' => (isset($request->relation_id) && !empty($request->relation_id) ? $request->relation_id : null),
                
                'updated_by' => $user_id,
            ]);
            $this->checkAndUpdateRecordHistory($gasBoilerSCC->id);

            if(!empty($appliances) && $gasBoilerSCC->id):
                $appliance_serial = (isset($appliances->appliance_serial) && $appliances->appliance_serial > 0 ? $appliances->appliance_serial : 1);
                $gasAppliance = GasBoilerSystemCommissioningChecklistAppliance::updateOrCreate(['gas_boiler_system_commissioning_checklist_id' => $gasBoilerSCC->id, 'appliance_serial' => $appliance_serial], [
                    'gas_boiler_system_commissioning_checklist_id' => $gasBoilerSCC->id,
                    'appliance_serial' => $appliance_serial,
                    
                    'boiler_brand_id' => (isset($appliances->boiler_brand_id) && !empty($appliances->boiler_brand_id) ? $appliances->boiler_brand_id : null),
                    'model' => (isset($appliances->model) && !empty($appliances->model) ? $appliances->model : null),
                    'serial_no' => (isset($appliances->serial_no) && !empty($appliances->serial_no) ? $appliances->serial_no : null),
                    'appliance_time_temperature_heating_id' => (isset($appliances->appliance_time_temperature_heating_id) && !empty($appliances->appliance_time_temperature_heating_id) ? $appliances->appliance_time_temperature_heating_id : null),
                    
                    'tmp_control_hot_water' => (isset($appliances->tmp_control_hot_water) && !empty($appliances->tmp_control_hot_water) ? $appliances->tmp_control_hot_water : null),
                    'heating_zone_vlv' => (isset($appliances->heating_zone_vlv) && !empty($appliances->heating_zone_vlv) ? $appliances->heating_zone_vlv : null),
                    'hot_water_zone_vlv' => (isset($appliances->hot_water_zone_vlv) && !empty($appliances->hot_water_zone_vlv) ? $appliances->hot_water_zone_vlv : null),
                    'therm_radiator_vlv' => (isset($appliances->therm_radiator_vlv) && !empty($appliances->therm_radiator_vlv) ? $appliances->therm_radiator_vlv : null),
                    'bypass_to_system' => (isset($appliances->bypass_to_system) && !empty($appliances->bypass_to_system) ? $appliances->bypass_to_system : null),
                    'boiler_interlock' => (isset($appliances->boiler_interlock) && !empty($appliances->boiler_interlock) ? $appliances->boiler_interlock : null),
                    'flushed_and_cleaned' => (isset($appliances->flushed_and_cleaned) && !empty($appliances->flushed_and_cleaned) ? $appliances->flushed_and_cleaned : null),
                    'clearner_name' => (isset($appliances->clearner_name) && !empty($appliances->clearner_name) ? $appliances->clearner_name : null),
                    'inhibitor_quantity' => (isset($appliances->inhibitor_quantity) && !empty($appliances->inhibitor_quantity) ? $appliances->inhibitor_quantity : null),
                    'inhibitor_amount' => (isset($appliances->inhibitor_amount) && !empty($appliances->inhibitor_amount) ? $appliances->inhibitor_amount : null),
                    'primary_ws_filter_installed' => (isset($appliances->primary_ws_filter_installed) && !empty($appliances->primary_ws_filter_installed) ? $appliances->primary_ws_filter_installed : null),
                    'gas_rate' => (isset($appliances->gas_rate) && !empty($appliances->gas_rate) ? $appliances->gas_rate : null),
                    'gas_rate_unit' => (isset($appliances->gas_rate_unit) && !empty($appliances->gas_rate_unit) ? $appliances->gas_rate_unit : null),
                    'cho_factory_setting' => (isset($appliances->cho_factory_setting) && !empty($appliances->cho_factory_setting) ? $appliances->cho_factory_setting : null),
                    'burner_opt_pressure' => (isset($appliances->burner_opt_pressure) && !empty($appliances->burner_opt_pressure) ? $appliances->burner_opt_pressure : null),
                    'burner_opt_pressure_unit' => (isset($appliances->burner_opt_pressure_unit) && !empty($appliances->burner_opt_pressure_unit) ? $appliances->burner_opt_pressure_unit : null),
                    'centeral_heat_flow_temp' => (isset($appliances->centeral_heat_flow_temp) && !empty($appliances->centeral_heat_flow_temp) ? $appliances->centeral_heat_flow_temp : null),
                    'centeral_heat_return_temp' => (isset($appliances->centeral_heat_return_temp) && !empty($appliances->centeral_heat_return_temp) ? $appliances->centeral_heat_return_temp : null),
                    'is_in_hard_water_area' => (isset($appliances->is_in_hard_water_area) && !empty($appliances->is_in_hard_water_area) ? $appliances->is_in_hard_water_area : null),
                    'is_scale_reducer_fitted' => (isset($appliances->is_scale_reducer_fitted) && !empty($appliances->is_scale_reducer_fitted) ? $appliances->is_scale_reducer_fitted : null),
                    'what_reducer_fitted' => (isset($appliances->what_reducer_fitted) && !empty($appliances->what_reducer_fitted) ? $appliances->what_reducer_fitted : null),
                    'dom_gas_rate' => (isset($appliances->dom_gas_rate) && !empty($appliances->dom_gas_rate) ? $appliances->dom_gas_rate : null),
                    'dom_gas_rate_unit' => (isset($appliances->dom_gas_rate_unit) && !empty($appliances->dom_gas_rate_unit) ? $appliances->dom_gas_rate_unit : null),
                    'dom_burner_opt_pressure' => (isset($appliances->dom_burner_opt_pressure) && !empty($appliances->dom_burner_opt_pressure) ? $appliances->dom_burner_opt_pressure : null),
                    'dom_burner_opt_pressure_unit' => (isset($appliances->dom_burner_opt_pressure_unit) && !empty($appliances->dom_burner_opt_pressure_unit) ? $appliances->dom_burner_opt_pressure_unit : null),
                    'dom_cold_water_temp' => (isset($appliances->dom_cold_water_temp) && !empty($appliances->dom_cold_water_temp) ? $appliances->dom_cold_water_temp : null),
                    'dom_checked_outlet' => (isset($appliances->dom_checked_outlet) && !empty($appliances->dom_checked_outlet) ? $appliances->dom_checked_outlet : null),
                    'dom_water_flow_rate' => (isset($appliances->dom_water_flow_rate) && !empty($appliances->dom_water_flow_rate) ? $appliances->dom_water_flow_rate : null),
                    'con_drain_installed' => (isset($appliances->con_drain_installed) && !empty($appliances->con_drain_installed) ? $appliances->con_drain_installed : null),
                    'point_of_termination' => (isset($appliances->point_of_termination) && !empty($appliances->point_of_termination) ? $appliances->point_of_termination : null),
                    'dispsal_method' => (isset($appliances->dispsal_method) && !empty($appliances->dispsal_method) ? $appliances->dispsal_method : null),
                    'min_ratio' => (isset($appliances->min_ratio) && !empty($appliances->min_ratio) ? $appliances->min_ratio : null),
                    'min_co' => (isset($appliances->min_co) && !empty($appliances->min_co) ? $appliances->min_co : null),
                    'min_co2' => (isset($appliances->min_co2) && !empty($appliances->min_co2) ? $appliances->min_co2 : null),
                    'max_ratio' => (isset($appliances->max_ratio) && !empty($appliances->max_ratio) ? $appliances->max_ratio : null),
                    'max_co' => (isset($appliances->max_co) && !empty($appliances->max_co) ? $appliances->max_co : null),
                    'max_co2' => (isset($appliances->max_co2) && !empty($appliances->max_co2) ? $appliances->max_co2 : null),
                    'app_building_regulation' => (isset($appliances->app_building_regulation) && !empty($appliances->app_building_regulation) ? $appliances->app_building_regulation : null),
                    'commissioned_man_ins' => (isset($appliances->commissioned_man_ins) && !empty($appliances->commissioned_man_ins) ? $appliances->commissioned_man_ins : null),
                    'demonstrated_understood' => (isset($appliances->demonstrated_understood) && !empty($appliances->demonstrated_understood) ? $appliances->demonstrated_understood : null),
                    'literature_including' => (isset($appliances->literature_including) && !empty($appliances->literature_including) ? $appliances->literature_including : null),
                    'is_next_inspection' => (isset($appliances->is_next_inspection) && !empty($appliances->is_next_inspection) ? $appliances->is_next_inspection : null),
                    
                    'updated_by' => $user_id,
                ]);
            endif;

            if($request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $gasBoilerSCC->deleteSignature();
                    
                    $imageName = 'signatures/' . Str::uuid() . '.png';
                    Storage::disk('public')->put($imageName, $signatureData);
                    $signature = new Signature();
                    $signature->model_type = GasBoilerSystemCommissioningChecklist::class;
                    $signature->model_id = $gasBoilerSCC->id;
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
                'data' => [
                    'id' => $gasBoilerSCC->id
                ],
            ], 200);
        else:
            return response()->json(['message' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function getDetails($checklist_id){
        try {
            $boilerCommChecklist = GasBoilerSystemCommissioningChecklist::with(['customer', 'appliance', 'signature'])->findOrFail($checklist_id);
            return response()->json([
                    'success' => true,
                    'data' => $boilerCommChecklist
                ], 200);

            } catch (Exception $e) {
                return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve boiler commissioning checklist. The requested checklist (ID: '.$checklist_id.') was not found or may have been deleted.',
                'error_details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 404);
        }
    }
}
