<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPricingPackage extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'pricing_package_id',
        'start',
        'end',
        'price',
        'active',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package(){
        return $this->belongsTo(PricingPackage::class, 'pricing_package_id');
    }
}
