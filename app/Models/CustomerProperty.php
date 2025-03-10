<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProperty extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['full_address', 'full_address_html'];
    
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

    public function contact(){
        return $this->belongsTo(CustomerContactInformation::class, 'customer_id');
    }

    public function getFullAddressAttribute(){
        $address = '';
        $address .= $this->address_line_1.' '.$this->address_line_2.', ';
        $address .= (!empty($this->city) ? $this->city.', ' : '');
        $address .= (!empty($this->postal_code) ? $this->postal_code.', ' : '');
        $address .= (!empty($this->state) ? $this->state.', ' : '');
        $address .= (!empty($this->country) ? $this->country : '');
        return $address;
    }

    public function getFullAddressHtmlAttribute(){
        $address = '';
        $address .= $this->address_line_1.' '.$this->address_line_2.',<br/> ';
        $address .= (!empty($this->city) ? $this->city.', ' : '');
        $address .= (!empty($this->postal_code) ? $this->postal_code.',<br/> ' : '');
        $address .= (!empty($this->state) ? $this->state.',<br/> ' : '');
        $address .= (!empty($this->country) ? $this->country : '');
        return $address;
    }
}
