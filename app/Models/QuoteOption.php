<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteOption extends Model
{
    public $timestamps = false;

    protected $casts = ['value' => 'object'];

    protected $fillable = [
        'quote_id',
        'name',
        'value',
    ];
}
