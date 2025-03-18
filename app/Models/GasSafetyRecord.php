<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;

class GasSafetyRecord extends Model implements CanBeSigned
{
    use HasFactory, SoftDeletes, RequiresSignature;

    protected $appends = ['has_coalarm', 'has_satisfactory_check', 'has_comments', 'has_signatures'];
    
    protected $fillable = [
        'customer_id',
        'customer_job_id',
        'job_form_id',
        'cp_alarm_fitted',
        'cp_alarm_satisfactory',
        'satisfactory_visual_inspaction',
        'emergency_control_accessible',
        'satisfactory_gas_tightness_test',
        'equipotential_bonding_satisfactory',
        'co_alarm_fitted',
        'co_alarm_in_date',
        'co_alarm_test_satisfactory',
        'smoke_alarm_fitted',
        'fault_details',
        'rectification_work_carried_out',
        'details_work_carried_out',
        'flue_cap_put_back',
        'inspection_date',
        'next_inspection_date',
        'received_by',
        'relation_id',
        'status',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function setInspectionDateAttribute($value) {  
        $this->attributes['inspection_date'] =  (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }

    public function getInspectionDateAttribute($value) {
        return (!empty($value) ? date('d-m-Y', strtotime($value)) : '');
    }

    public function setNextInspectionDateAttribute($value) {  
        $this->attributes['next_inspection_date'] =  (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }

    public function getNextInspectionDateAttribute($value) {
        return (!empty($value) ? date('d-m-Y', strtotime($value)) : '');
    }

    public function getHasSatisfactoryCheckAttribute(){
        return (
            !empty($this->satisfactory_visual_inspaction) || !empty($this->emergency_control_accessible) || !empty($this->satisfactory_gas_tightness_test) ||
            !empty($this->equipotential_bonding_satisfactory) || !empty($this->co_alarm_fitted) || !empty($this->co_alarm_in_date) || !empty($this->co_alarm_test_satisfactory) || 
            !empty($this->smoke_alarm_fitted)
            ? true 
            : false
        );
    }

    public function getHasCommentsAttribute(){
        return (
            !empty($this->fault_details) || !empty($this->rectification_work_carried_out) || !empty($this->details_work_carried_out) ||
            !empty($this->flue_cap_put_back)
            ? true 
            : false
        );
    }

    public function getHasSignaturesAttribute(){
        return (
            !empty($this->inspection_date) || !empty($this->next_inspection_date) || !empty($this->received_by) || !empty($this->relation_id)
            ? true 
            : false
        );
    }
}
