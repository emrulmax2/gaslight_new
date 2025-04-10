<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasUnventedHotWaterCylinderRecordSystem extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'gas_unvented_hot_water_cylinder_record_id',
        'type',
        'make',
        'model',
        'location',
        'serial_no',
        'gc_number',
        'direct_or_indirect',
        'boiler_solar_immersion',
        'capacity',
        'warning_label_attached',
        'water_pressure',
        'flow_rate',
        'fully_commissioned',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function guhwcr(){
        return $this->belongsTo(GasUnventedHotWaterCylinderRecord::class, 'gas_unvented_hot_water_cylinder_record_id');
    }
}
