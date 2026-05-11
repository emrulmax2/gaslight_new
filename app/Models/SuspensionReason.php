<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspensionReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
