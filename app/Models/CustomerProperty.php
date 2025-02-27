<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProperty extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'customer_id',
        'address_line_1',
        'address_line_2',
        'postal_code',
        'state',
        'city',
        'country',
        'latitude',
        'longitude',
        'note',
        'occupant_name',
        'occupant_email',
        'occupant_phone',
        'due_date',

        'created_by',
        'updated_by'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
