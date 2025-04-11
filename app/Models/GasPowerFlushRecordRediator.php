<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasPowerFlushRecordRediator extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }
    
    protected $fillable = [
        'gas_power_flush_record_id',

        'rediator_location',
        'tmp_b_top',
        'tmp_b_bottom',
        'tmp_b_left',
        'tmp_b_right',
        'tmp_a_top',
        'tmp_a_bottom',
        'tmp_a_left',
        'tmp_a_right',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gpfr(){
        return $this->belongsTo(GasPowerFlushRecord::class, 'gas_power_flush_record_id');
    }
}
