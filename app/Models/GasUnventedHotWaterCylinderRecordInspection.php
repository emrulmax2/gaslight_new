<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasUnventedHotWaterCylinderRecordInspection extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }
    
    protected $fillable = [
        'gas_unvented_hot_water_cylinder_record_id',
        'system_opt_pressure',
        'opt_presure_exp_vsl',
        'opt_presure_exp_vlv',
        'tem_relief_vlv',
        'opt_temperature',
        'combined_temp_presr',
        'max_circuit_presr',
        'flow_temp',
        'd1_mormal_size',
        'd1_length',
        'd1_discharges_no',
        'd1_manifold_size',
        'd1_is_tundish_install_same_location',
        'd1_is_tundish_visible',
        'd1_is_auto_dis_intall',
        'd2_mormal_size',
        'd2_pipework_material',
        'd2_minimum_v_length',
        'd2_fall_continuously',
        'd2_termination_method',
        'd2_termination_satisfactory',
        'comments',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function guhwcr(){
        return $this->belongsTo(GasUnventedHotWaterCylinderRecord::class, 'gas_unvented_hot_water_cylinder_record_id');
    }
}
