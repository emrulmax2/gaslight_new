<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobFormPrefixMumbering extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_form_id',
        'prefix',
        'starting_from',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
