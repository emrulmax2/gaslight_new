<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasPowerFlushRecordChecklist extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }
    
    protected $fillable = [
        'gas_power_flush_record_id',

        'powerflush_system_type_id',
        'boiler_brand_id',
        'radiators',
        'pipework',
        'appliance_type_id',
        'appliance_location_id',
        'serial_no',
        'powerflush_cylinder_type_id',
        'powerflush_pipework_type_id',
        'twin_radiator_vlv_fitted',
        'completely_warm_on_fired',
        'circulation_for_all_readiators',
        'suffifiently_sound',
        'powerflush_circulator_pump_location_id',
        'number_of_radiators',
        'radiator_type_id',
        'getting_warm',
        'are_trvs_fitted',
        'sign_of_neglect',
        'radiator_open_fully',
        'number_of_valves',
        'valves_located',
        'fe_tank_location',
        'fe_tank_checked',
        'fe_tank_condition',
        'color_id',
        'before_color_id',
        'mw_ph',
        'mw_chloride',
        'mw_hardness',
        'mw_inhibitor',
        'bpf_ph',
        'bpf_chloride',
        'bpf_hardness',
        'bpf_inhibitor',
        'apf_ph',
        'apf_chloride',
        'apf_hardness',
        'apf_inhibitor',
        'mw_tds_reading',
        'bf_tds_reading',
        'af_tds_reading',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gpfr(){
        return $this->belongsTo(GasPowerFlushRecord::class, 'gas_power_flush_record_id');
    }

    public function systemType(){
        return $this->belongsTo(PowerflushSystemType::class, 'powerflush_system_type_id');
    }

    public function make(){
        return $this->belongsTo(BoilerBrand::class, 'boiler_brand_id');
    }

    public function type(){
        return $this->belongsTo(ApplianceType::class, 'appliance_type_id');
    }

    public function location(){
        return $this->belongsTo(ApplianceLocation::class, 'appliance_location_id');
    }

    public function cylinder(){
        return $this->belongsTo(PowerflushCylinderType::class, 'powerflush_cylinder_type_id');
    }

    public function pipeworkType(){
        return $this->belongsTo(PowerflushPipeworkType::class, 'powerflush_pipework_type_id');
    }

    public function pumpLocation(){
        return $this->belongsTo(PowerflushCirculatorPumpLocation::class, 'powerflush_circulator_pump_location_id');
    }

    public function rediatorType(){
        return $this->belongsTo(RadiatorType::class, 'radiator_type_id');
    }

    public function color(){
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function beforeColor(){
        return $this->belongsTo(Color::class, 'before_color_id');
    }
}
