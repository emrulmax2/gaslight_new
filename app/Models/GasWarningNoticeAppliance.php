<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasWarningNoticeAppliance extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }
    
    protected $fillable = [
        'gas_warning_notice_id',
        'appliance_serial',
        'appliance_location_id',
        'boiler_brand_id',
        'model',
        'appliance_type_id',
        'serial_no',
        'gc_no',
        'gas_warning_classification_id',
        'gas_escape_issue',
        'pipework_issue',
        'ventilation_issue',
        'meter_issue',
        'chimeny_issue',
        'fault_details',
        'action_taken',
        'actions_required',
        'reported_to_hse',
        'reported_to_hde',
        'left_on_premisies',

        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gwn(){
        return $this->belongsTo(GasWarningNotice::class, 'gas_warning_notice_id');
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

    public function classification(){
        return $this->belongsTo(GasWarningClassification::class, 'gas_warning_classification_id');
    }
}
