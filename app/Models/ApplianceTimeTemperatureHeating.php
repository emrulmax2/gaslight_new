<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplianceTimeTemperatureHeating extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'active',
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
