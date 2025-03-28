<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasBoilerSystemCommissioningChecklistAppliance extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'gas_boiler_system_commissioning_checklist_id',
        'appliance_serial',
        'boiler_brand_id',
        'model',
        'serial_no',
        'appliance_time_temperature_heating_id',
        
        'tmp_control_hot_water',
        'heating_zone_vlv',
        'hot_water_zone_vlv',
        'therm_radiator_vlv',
        'bypass_to_system',
        'boiler_interlock',
        'flushed_and_cleaned',
        'clearner_name',
        'inhibitor_quantity',
        'inhibitor_amount',
        'primary_ws_filter_installed',
        'gas_rate',
        'gas_rate_unit',
        'cho_factory_setting',
        'burner_opt_pressure',
        'burner_opt_pressure_unit',
        'centeral_heat_flow_temp',
        'centeral_heat_return_temp',
        'is_in_hard_water_area',
        'is_scale_reducer_fitted',
        'what_reducer_fitted',
        'dom_gas_rate',
        'dom_gas_rate_unit',
        'dom_burner_opt_pressure',
        'dom_burner_opt_pressure_unit',
        'dom_cold_water_temp',
        'dom_checked_outlet',
        'dom_water_flow_rate',
        'con_drain_installed',
        'point_of_termination',
        'dispsal_method',
        'min_ratio',
        'min_co',
        'min_co2',
        'max_ratio',
        'max_co',
        'max_co2',
        'app_building_regulation',
        'commissioned_man_ins',
        'demonstrated_understood',
        'literature_including',
        'is_next_inspection',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gbscc(){
        return $this->belongsTo(GasBoilerSystemCommissioningChecklist::class, 'gas_boiler_system_commissioning_checklist_id');
    }

    public function make(){
        return $this->belongsTo(BoilerBrand::class, 'boiler_brand_id');
    }

    public function temperature(){
        return $this->belongsTo(ApplianceTimeTemperatureHeating::class, 'appliance_time_temperature_heating_id');
    }
}
