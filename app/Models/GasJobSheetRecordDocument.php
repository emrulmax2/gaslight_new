<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GasJobSheetRecordDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }

    protected $appends = ['doc_url'];
    
    protected $fillable = [
        'gas_job_sheet_record_id',
        'name',
        'path',
        'mime_type',
        'size',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function gjsr(){
        return $this->belongsTo(GasJobSheetRecord::class, 'gas_job_sheet_record_id');
    }

    public function getDocUrlAttribute(){
        if (!empty($this->name) && Storage::disk('public')->exists('gjsr/'.$this->gjsr->customer_job_id.'/'.$this->gjsr->job_form_id.'/'.$this->name)):
            return Storage::disk('public')->url('gjsr/'.$this->gjsr->customer_job_id.'/'.$this->gjsr->job_form_id.'/'.$this->name);
        else:
            return false;
        endif;
    }
}
