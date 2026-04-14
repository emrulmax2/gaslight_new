<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmailRegisterOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'attempts'
    ];

    public function isExpired(){
        return Carbon::now()->gt($this->expires_at);
    }
}
