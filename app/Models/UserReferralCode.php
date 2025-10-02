<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReferralCode extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'is_global',
        'no_of_days',
        'expiry_date',
        'max_no_of_use',
        'active',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];

    public function setExpiryDateAttribute($value) {  
        $this->attributes['expiry_date'] = (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }


    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
