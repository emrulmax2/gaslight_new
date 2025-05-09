<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPricingPackageInvoice extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'user_pricing_package_id',
        'invoice_id',
        'start',
        'end',
        'status',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package(){
        return $this->belongsTo(UserPricingPackage::class, 'user_pricing_package_id');
    }
}
