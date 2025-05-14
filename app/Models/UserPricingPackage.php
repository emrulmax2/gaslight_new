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
        'stripe_customer_id',
        'stripe_subscription_id',
        'start',
        'end',
        'price',
        'active',
        'cancellation_requested',
        'requested_by',
        'requested_at',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
    

    public function setStartAttribute($value) {  
        $this->attributes['start'] =  (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }

    public function getStartAttribute($value) {
        return (!empty($value) ? date('d-m-Y', strtotime($value)) : '');
    }

    public function setEndAttribute($value) {  
        $this->attributes['end'] =  (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }

    public function getEndAttribute($value) {
        return (!empty($value) ? date('d-m-Y', strtotime($value)) : '');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package(){
        return $this->belongsTo(PricingPackage::class, 'pricing_package_id');
    }
}
