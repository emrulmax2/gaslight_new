<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceOption extends Model
{
    public $timestamps = false;

    protected $casts = ['value' => 'object'];

    protected $fillable = [
        'invoice_id',
        'name',
        'value',
    ];
}
