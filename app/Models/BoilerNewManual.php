<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BoilerNewManual extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'boiler_new_brand_id',
        'gc_no',
        'url',
        'model',
        'fuel_type',
        'year_of_manufacture',
        'document',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
    */
    protected $appends = ['pdf_url'];

    /**
     * Get the boiler brand that this manual belongs to.
     *
     * @return BelongsTo<BoilerBrand, BoilerNewManual>
    */
    public function boilerBrand(): BelongsTo
    {
        return $this->belongsTo(BoilerBrand::class);
    }

    /**
     * Get the full public URL of the boiler manual PDF.
     *
     * @return string|false
    */
    public function getPdfUrlAttribute(){
        if (!empty($this->document) && Storage::disk('public')->exists('boiler-new-brand/'.$this->boiler_new_brand_id.'/'.$this->document)):
            return Storage::disk('public')->url('boiler-new-brand/'.$this->boiler_new_brand_id.'/'.$this->document);
        else:
            return false;
        endif;
    }
}
