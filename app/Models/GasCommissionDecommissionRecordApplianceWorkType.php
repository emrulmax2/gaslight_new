<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasCommissionDecommissionRecordApplianceWorkType extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'gas_commission_decommission_record_appliance_id',
        'commission_decommission_work_type_id',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gcdra(){
        return $this->belongsTo(GasCommissionDecommissionRecordAppliance::class, 'gas_commission_decommission_record_appliance_id');
    }

    public function cdwt(){
        return $this->belongsTo(CommissionDecommissionWorkType::class, 'commission_decommission_work_type_id');
    }
}
