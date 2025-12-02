<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceCancelReason extends Model
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
