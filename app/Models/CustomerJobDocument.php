<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerJobDocument extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'customer_id',
        'customer_job_id',
        'display_file_name',
        'current_file_name',
        'doc_type',
        'disk_type',
        'path',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];
}
