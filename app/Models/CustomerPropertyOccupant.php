<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPropertyOccupant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_property_id',
        'occupant_name',
        'occupant_email',
        'occupant_phone',
        'due_date',
        'active',

        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function property(){
        return $this->belongsTo(CustomerProperty::class, 'customer_property_id');
    }

    public function setDueDateAttribute($value) {  
        $this->attributes['due_date'] =  (!empty($value) ? date('Y-m-d', strtotime($value)) : null);
    }

    public function getDueDateAttribute($value) {
        return (!empty($value) ? date('d-m-Y', strtotime($value)) : '');
    }
}
