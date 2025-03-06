<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoilerManual extends Model
{
    protected $guarded = ['id'];

    public function boilerBrand()
    {
        return $this->belongsTo(BoilerBrand::class);
    }
}
