<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePayment extends Model
{
    use SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }

    protected $fillable = [
        'invoice_id',
        'payment_date',
        'payment_method_id',
        'amount',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];
}
