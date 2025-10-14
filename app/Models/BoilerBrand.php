<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoilerBrand extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'active'
    ];
    
    public function boilerManuals()
    {
        return $this->hasMany(BoilerManual::class);
    }
}
