<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;

class GasWarningNotice extends Model implements CanBeSigned
{
    use HasFactory, SoftDeletes, RequiresSignature;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }

    protected $fillable = [
        'customer_id',
        'customer_job_id',
        'job_form_id',
        'certificate_number',
        'inspection_date',
        'next_inspection_date',
        'received_by',
        'relation_id',
        'status',

        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function job(){
        return $this->belongsTo(CustomerJob::class, 'customer_job_id');
    }

    public function form(){
        return $this->belongsTo(JobForm::class, 'job_form_id');
    }

    public function appliance(){
        return $this->hasMany(GasWarningNoticeAppliance::class, 'gas_warning_notice_id', 'id')->orderBy('id', 'ASC');
    }

    public function relation(){
        return $this->belongsTo(Relation::class, 'relation_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

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

    public function getHasSignaturesAttribute(){
        return (
            !empty($this->inspection_date) || !empty($this->next_inspection_date) || !empty($this->received_by) || !empty($this->relation_id)
            ? true 
            : false
        );
    }
}
