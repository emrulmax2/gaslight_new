<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BoilerManual extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['pdf_url'];

    public function boilerBrand()
    {
        return $this->belongsTo(BoilerBrand::class);
    }

    public function getPdfUrlAttribute(){
        if (!empty($this->document) && Storage::disk('public')->exists('boiler_manual/'.$this->id.'/'.$this->document)):
            return Storage::disk('public')->url('boiler_manual/'.$this->id.'/'.$this->document);
        else:
            return false;
        endif;
    }
}
