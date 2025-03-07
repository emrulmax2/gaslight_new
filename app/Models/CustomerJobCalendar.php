<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerJobCalendar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'customer_customer_job_id',
        'date',
        'calendar_time_slot_id',
        'status',
        'completed_at',
        'completed_by',
        'cancelled_at',
        'cancelled_by',

        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];
    
    public function setDateAttribute($value) {  
        $this->attributes['date'] =  (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }

    public function getDateAttribute($value) {
        return (!empty($value) ? date('d-m-Y', strtotime($value)) : '');
    }

    public function job(){
        return $this->belongsTo(CustomerJob::class, 'customer_customer_job_id');
    }

    public function slot(){
        return $this->belongsTo(CalendarTimeSlot::class, 'calendar_time_slot_id');
    }
}
