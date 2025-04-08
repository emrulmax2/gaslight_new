<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingPackage extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'period',
        'price',
        'order',
        'active',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
