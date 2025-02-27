<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerContactInformation extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'customer_id',
        'mobile',
        'phone',
        'email',
        'other_email',

        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
