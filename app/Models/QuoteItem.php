<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteItem extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'quote_id',
        'type',
        'description',
        'units',
        'unit_price',
        'vat_rate',
        'vat_amount',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];
}
