<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasServiceRecordAppliance extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'gas_service_record_id',
        'appliance_serial',
        'appliance_location_id',
        'boiler_brand_id',
        'model',
        'appliance_type_id',
        'gc_no',
        'serial_no',
        
        'opt_pressure',
        'rented_accommodation',
        'type_of_work_carried_out',
        'test_carried_out',
        'is_electricial_bonding',
        'low_analyser_ratio',
        'low_co',
        'low_co2',
        'high_analyser_ratio',
        'high_co',
        'high_co2',

        'heat_exchanger',
        'heat_exchanger_detail',
        'burner_injectors',
        'burner_injectors_detail',
        'flame_picture',
        'flame_picture_detail',
        'ignition',
        'ignition_detail',
        'electrics',
        'electrics_detail',
        'controls',
        'controls_detail',
        'leak_gas_water',
        'leak_gas_water_detail',
        'seals',
        'seals_detail',
        'pipework',
        'pipework_detail',
        'fans',
        'fans_detail',
        'fireplace',
        'fireplace_detail',
        'closure_plate',
        'closure_plate_detail',
        'allowable_location',
        'allowable_location_detail',
        'boiler_ratio',
        'boiler_ratio_detail',
        'stability',
        'stability_detail',
        'return_air_ple',
        'return_air_ple_detail',
        'ventillation',
        'ventillation_detail',
        'flue_termination',
        'flue_termination_detail',
        'smoke_pellet_flue_flow',
        'smoke_pellet_flue_flow_detail',
        'smoke_pellet_spillage',
        'smoke_pellet_spillage_detail',
        'working_pressure',
        'working_pressure_detail',
        'savety_devices',
        'savety_devices_detail',
        'gas_tightness',
        'gas_tightness_detail',
        'expansion_vassel_checked',
        'expansion_vassel_checked_detail',
        'other_regulations',
        'other_regulations_detail',
        'is_safe_to_use',
        'instruction_followed',
        'work_required_note',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gsr(){
        return $this->belongsTo(GasServiceRecord::class, 'gas_safety_record_id');
    }

    public function location(){
        return $this->belongsTo(ApplianceLocation::class, 'appliance_location_id');
    }

    public function type(){
        return $this->belongsTo(ApplianceType::class, 'appliance_type_id');
    }

    public function make(){
        return $this->belongsTo(BoilerBrand::class, 'boiler_brand_id');
    }
}
