<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordInspectionNotification extends Model
{
    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }
    
    protected $fillable = [
        'record_id',
        'inspection_date',
        'sent_at',
        
        'created_by',
        'updated_by'
    ];
}
