<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasCommissionDecommissionRecordAppliance extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'gas_commission_decommission_record_id',
        'appliance_serial',
        'details_work_carried_out',
        'details_work_required',
        'is_safe_to_use',
        'have_labels_affixed',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gcdr(){
        return $this->belongsTo(GasCommissionDecommissionRecord::class, 'gas_commission_decommission_record_id');
    }

    public function gcdrawt(){
        return $this->hasMany(GasCommissionDecommissionRecordApplianceWorkType::class, 'gas_commission_decommission_record_appliance_id', 'id');
    }
}
