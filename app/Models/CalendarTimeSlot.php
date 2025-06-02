<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarTimeSlot extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['slot_title'];
    
    protected $fillable = [
        'title',
        'start',
        'end',
        'color_code',
        'active',

        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function getSlotTitleAttribute() {
        $title = $this->title;
        $title .= ' ('.date('h:i A', strtotime($this->start)).' - '.date('h:i A', strtotime($this->end)).')';

        return $title;
    }
}
