<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasSafetyRecordAppliance extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }
    
    protected $fillable = [
        'gas_safety_record_id',
        'appliance_serial',
        'appliance_location_id',
        'boiler_brand_id',
        'model',
        'appliance_type_id',
        'serial_no',
        'gc_no',
        'appliance_flue_type_id',
        'opt_pressure',
        'safety_devices',
        'spillage_test',
        'smoke_pellet_test',
        'low_analyser_ratio',
        'low_co',
        'low_co2',
        'high_analyser_ratio',
        'high_co',
        'high_co2',
        'satisfactory_termination',
        'flue_visual_condition',
        'adequate_ventilation',
        'landlord_appliance',
        'inspected',
        'appliance_visual_check',
        'appliance_serviced',
        'appliance_safe_to_use',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gsr(){
        return $this->belongsTo(GasSafetyRecord::class, 'gas_safety_record_id');
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

    public function flue(){
        return $this->belongsTo(ApplianceFlueType::class, 'appliance_flue_type_id');
    }
}
