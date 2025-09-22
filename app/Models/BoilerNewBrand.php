<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoilerNewBrand extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['logo_url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'logo',
    ];


    protected $dates = ['deleted_at'];
    

    /**
     * Get all manuals associated with this boiler brand.
     *
     * @return HasMany<BoilerNewManual>
    */
    public function boilerNewManuals(): HasMany
    {
        return $this->hasMany(BoilerNewManual::class);
    }

    /**
     * The getter that return accessible URL for user photo.
     *
     * @var array
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo !== null && Storage::disk('public')->exists('boiler-new-brand/'.$this->id.'/'.$this->logo)) {
            return Storage::disk('public')->url('boiler-new-brand/'.$this->id.'/'.$this->logo);
        } else {
            return Vite::asset('resources/images/placeholders/200x200.jpg');
        }
    }
}
