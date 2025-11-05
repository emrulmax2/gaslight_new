<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'record_id',
        'action',
        'subject',
        'content',
        'note',
        'created_by'
    ];

    public function record(){
        return $this->belongsTo(Record::class, 'record_id');
    }
}
