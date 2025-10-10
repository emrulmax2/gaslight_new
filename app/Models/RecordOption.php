<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordOption extends Model
{
    public $timestamps = false;

    protected $casts = ['value' => 'object'];

    protected $fillable = [
        'record_id',
        'job_form_id',
        'name',
        'value',
    ];
}
