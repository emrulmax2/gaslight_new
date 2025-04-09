<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReferred extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_referral_code_id',
        'referrer_id',
        'referee_id',
        'code',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
