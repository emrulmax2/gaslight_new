<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasJobSheetRecordDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }
    
    protected $fillable = [
        'gas_job_sheet_record_id',
        'date',
        'job_note',
        'spares_required',
        'job_ref',
        'arrival_time',
        'departure_time',
        'hours_used',
        'awaiting_parts',
        'job_completed',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gjsr(){
        return $this->belongsTo(GasJobSheetRecord::class, 'gas_job_sheet_record_id');
    }

    public function setDateAttribute($value) {  
        $this->attributes['date'] =  (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }

    public function getDateAttribute($value) {
        return (!empty($value) ? date('d-m-Y', strtotime($value)) : '');
    }
}
