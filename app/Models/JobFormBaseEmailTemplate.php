<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobFormBaseEmailTemplate extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'job_form_id',
        'subject',
        'content',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
