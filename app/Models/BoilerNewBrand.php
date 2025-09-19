<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoilerNewBrand extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'name',
    ];
    

    /**
     * Get all manuals associated with this boiler brand.
     *
     * @return HasMany<BoilerNewManual>
    */
    public function boilerNewManuals(): HasMany
    {
        return $this->hasMany(BoilerNewManual::class);
    }
}
