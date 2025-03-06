<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoilerBrand extends Model
{
    
    protected $fillable = [
        'name',
    ];
    
    public function boilerManuals()
    {
        return $this->hasMany(BoilerManual::class);
    }
}
