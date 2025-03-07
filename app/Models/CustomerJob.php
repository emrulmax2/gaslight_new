<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerJob extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'customer_id',
        'customer_property_id',
        'description',
        'details',
        'customer_job_priority_id',
        'due_date',
        'customer_job_status_id',
        'reference_no',
        'estimated_amount',

        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function setDueDateAttribute($value) {  
        $this->attributes['due_date'] =  (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }

    public function getDueDateAttribute($value) {
        return (!empty($value) ? date('d-m-Y', strtotime($value)) : '');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function property(){
        return $this->belongsTo(CustomerProperty::class, 'customer_property_id');
    }

    public function priority(){
        return $this->belongsTo(CustomerJobPriority::class, 'customer_job_priority_id');
    }

    public function status(){
        return $this->belongsTo(CustomerJobStatus::class, 'customer_job_status_id');
    }

    public function calendar(){
        return $this->hasOne(CustomerJobCalendar::class, 'customer_customer_job_id', 'id');
    }
}
