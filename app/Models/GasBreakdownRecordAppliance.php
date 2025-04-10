<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasBreakdownRecordAppliance extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }
    
    protected $fillable = [
        'gas_breakdown_record_id',
        'appliance_serial',
        'appliance_location_id',
        'boiler_brand_id',
        'model',
        'appliance_type_id',
        'gc_no',
        'serial_no',
        
        'performance_analyser_ratio',
        'performance_co',
        'performance_co2',
        'opt_correctly',
        'conf_safety_standards',
        'notice_exlained',
        'flueing_is_safe',
        'ventilation_is_safe',
        'emition_combustion_test',
        'burner_pressure',
        'location_of_fault',
        'fault_resolved',
        'parts_fitted',
        'fitted_parts_name',
        'parts_required',
        'required_parts_name',
        'monoxide_alarm_fitted',
        'is_safe',
        'parts_available',
        'recommend_replacement',
        'magnetic_filter_fitted',
        'improvement_recommended',
        'enginner_comments',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gbr(){
        return $this->belongsTo(GasBreakdownRecord::class, 'gas_breakdown_record_id');
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
