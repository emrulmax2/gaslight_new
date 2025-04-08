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
        'active',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
