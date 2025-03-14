<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerJob;
use App\Models\CustomerProperty;
use App\Models\GasSafetyRecord;
use App\Models\GasSafetyRecordAppliance;
use App\Models\JobForm;
use Illuminate\Http\Request;

class HomeOwnerGasSafetyController extends Controller
{
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

    public function storeAppliance(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;
        $serial = $request->appliance_serial;
        $appliance = (isset($request->app) && !empty($request->app) ? $request->app : []);

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        $saved = 0;
        if($gasSafetyRecord->id && isset($appliance[$serial]) && !empty($appliance[$serial])):
            $theAppliance = $appliance[$serial];
            $appliance_location_id = (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null);
            if(!empty($appliance_location_id)):
                $gasAppliance = GasSafetyRecordAppliance::updateOrCreate(['gas_safety_record_id' => $gasSafetyRecord->id, 'appliance_serial' => $serial], [
                    'gas_safety_record_id' => $gasSafetyRecord->id,
                    'appliance_serial' => $serial,
                    'appliance_location_id' => (isset($theAppliance['appliance_location_id']) && !empty($theAppliance['appliance_location_id']) ? $theAppliance['appliance_location_id'] : null),
                    'boiler_brand_id' => (isset($theAppliance['boiler_brand_id']) && !empty($theAppliance['boiler_brand_id']) ? $theAppliance['boiler_brand_id'] : null),
                    'model' => (isset($theAppliance['model']) && !empty($theAppliance['model']) ? $theAppliance['model'] : null),
                    'appliance_type_id' => (isset($theAppliance['appliance_type_id']) && !empty($theAppliance['appliance_type_id']) ? $theAppliance['appliance_type_id'] : null),
                    'serial_no' => (isset($theAppliance['serial_no']) && !empty($theAppliance['serial_no']) ? $theAppliance['serial_no'] : null),
                    'gc_no' => (isset($theAppliance['gc_no']) && !empty($theAppliance['gc_no']) ? $theAppliance['gc_no'] : null),
                    'appliance_flue_type_id' => (isset($theAppliance['appliance_flue_type_id']) && !empty($theAppliance['appliance_flue_type_id']) ? $theAppliance['appliance_flue_type_id'] : null),
                    'opt_pressure' => (isset($theAppliance['opt_pressure']) && !empty($theAppliance['opt_pressure']) ? $theAppliance['opt_pressure'] : null),
                    'safety_devices' => (isset($theAppliance['safety_devices']) && !empty($theAppliance['safety_devices']) ? $theAppliance['safety_devices'] : null),
                    'spillage_test' => (isset($theAppliance['spillage_test']) && !empty($theAppliance['spillage_test']) ? $theAppliance['spillage_test'] : null),
                    'smoke_pellet_test' => (isset($theAppliance['smoke_pellet_test']) && !empty($theAppliance['smoke_pellet_test']) ? $theAppliance['smoke_pellet_test'] : null),
                    'low_analyser_ratio' => (isset($theAppliance['low_analyser_ratio']) && !empty($theAppliance['low_analyser_ratio']) ? $theAppliance['low_analyser_ratio'] : null),
                    'low_co' => (isset($theAppliance['low_co']) && !empty($theAppliance['low_co']) ? $theAppliance['low_co'] : null),
                    'low_co2' => (isset($theAppliance['low_co2']) && !empty($theAppliance['low_co2']) ? $theAppliance['low_co2'] : null),
                    'high_analyser_ratio' => (isset($theAppliance['high_analyser_ratio']) && !empty($theAppliance['high_analyser_ratio']) ? $theAppliance['high_analyser_ratio'] : null),
                    'high_co' => (isset($theAppliance['high_co']) && !empty($theAppliance['high_co']) ? $theAppliance['high_co'] : null),
                    'high_co2' => (isset($theAppliance['high_co2']) && !empty($theAppliance['high_co2']) ? $theAppliance['high_co2'] : null),
                    'satisfactory_termination' => (isset($theAppliance['satisfactory_termination']) && !empty($theAppliance['satisfactory_termination']) ? $theAppliance['satisfactory_termination'] : null),
                    'flue_visual_condition' => (isset($theAppliance['flue_visual_condition']) && !empty($theAppliance['flue_visual_condition']) ? $theAppliance['flue_visual_condition'] : null),
                    'adequate_ventilation' => (isset($theAppliance['adequate_ventilation']) && !empty($theAppliance['adequate_ventilation']) ? $theAppliance['adequate_ventilation'] : null),
                    'landlord_appliance' => (isset($theAppliance['landlord_appliance']) && !empty($theAppliance['landlord_appliance']) ? $theAppliance['landlord_appliance'] : null),
                    'inspected' => (isset($theAppliance['inspected']) && !empty($theAppliance['inspected']) ? $theAppliance['inspected'] : null),
                    'appliance_visual_check' => (isset($theAppliance['appliance_visual_check']) && !empty($theAppliance['appliance_visual_check']) ? $theAppliance['appliance_visual_check'] : null),
                    'appliance_serviced' => (isset($theAppliance['appliance_serviced']) && !empty($theAppliance['appliance_serviced']) ? $theAppliance['appliance_serviced'] : null),
                    'appliance_safe_to_use' => (isset($theAppliance['appliance_safe_to_use']) && !empty($theAppliance['appliance_safe_to_use']) ? $theAppliance['appliance_safe_to_use'] : null),
                    
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

    public function storeCoAlarms(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'cp_alarm_fitted' => (isset($request->cp_alarm_fitted) && !empty($request->cp_alarm_fitted) ? $request->cp_alarm_fitted : null),
            'cp_alarm_satisfactory' => (isset($request->cp_alarm_satisfactory) && !empty($request->cp_alarm_satisfactory) ? $request->cp_alarm_satisfactory : null),
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        return response()->json(['msg' => 'Customer Details successfully updated.', 'saved' => 1], 200);
    }

    public function storeSatisfactoryCheck(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'satisfactory_visual_inspaction' => (isset($request->satisfactory_visual_inspaction) && !empty($request->satisfactory_visual_inspaction) ? $request->satisfactory_visual_inspaction : null),
            'emergency_control_accessible' => (isset($request->emergency_control_accessible) && !empty($request->emergency_control_accessible) ? $request->emergency_control_accessible : null),
            'satisfactory_gas_tightness_test' => (isset($request->satisfactory_gas_tightness_test) && !empty($request->satisfactory_gas_tightness_test) ? $request->satisfactory_gas_tightness_test : null),
            'equipotential_bonding_satisfactory' => (isset($request->equipotential_bonding_satisfactory) && !empty($request->equipotential_bonding_satisfactory) ? $request->satisfactory_gas_tightness_test : null),
            'co_alarm_fitted' => (isset($request->co_alarm_fitted) && !empty($request->co_alarm_fitted) ? $request->co_alarm_fitted : null),
            'co_alarm_in_date' => (isset($request->co_alarm_in_date) && !empty($request->co_alarm_in_date) ? $request->co_alarm_in_date : null),
            'co_alarm_test_satisfactory' => (isset($request->co_alarm_test_satisfactory) && !empty($request->co_alarm_test_satisfactory) ? $request->co_alarm_test_satisfactory : null),
            'smoke_alarm_fitted' => (isset($request->smoke_alarm_fitted) && !empty($request->smoke_alarm_fitted) ? $request->smoke_alarm_fitted : null),
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        return response()->json(['msg' => 'Customer Details successfully updated.', 'saved' => 1], 200);
    }

    public function storeComments(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'job_form_id' => $job_form_id,

            'fault_details' => (isset($request->fault_details) && !empty($request->fault_details) ? $request->fault_details : null),
            'rectification_work_carried_out' => (isset($request->rectification_work_carried_out) && !empty($request->rectification_work_carried_out) ? $request->rectification_work_carried_out : null),
            'details_work_carried_out' => (isset($request->details_work_carried_out) && !empty($request->details_work_carried_out) ? $request->details_work_carried_out : null),
            'flue_cap_put_back' => (isset($request->flue_cap_put_back) && !empty($request->flue_cap_put_back) ? $request->flue_cap_put_back : null),
            
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        return response()->json(['msg' => 'Customer Details successfully updated.', 'saved' => 1], 200);
    }

    public function storeSignatures(Request $request){
        $customer_job_id = $request->customer_job_id;
        $job_form_id = $request->job_form_id;

        $job = CustomerJob::with('customer', 'customer.contact', 'property')->find($customer_job_id);
        $form = JobForm::find($job_form_id);
        $user_id = auth()->user()->id;

        $gasSafetyRecord = GasSafetyRecord::updateOrCreate([ 'customer_job_id' => $customer_job_id, 'job_form_id' => $job_form_id ], [
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

        return response()->json(['msg' => 'Customer Details successfully updated.', 'saved' => 1], 200);
    }
}
