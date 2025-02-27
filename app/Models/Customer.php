<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['full_name', 'full_address'];
    
    protected $fillable = [
        'title_id',
        'first_name',
        'last_name',
        'company_name',
        'vat_no',
        'address_line_1',
        'address_line_2',
        'postal_code',
        'state',
        'city',
        'country',
        'latitude',
        'longitude',
        'note',
        'auto_reminder',

        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];

    public function getFullNameAttribute(){
        return (isset($this->title->name) && !empty($this->title->name) ? $this->title->name.' ' : '').$this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute(){
        $address = '';
        $address .= $this->address_line_1.' '.$this->address_line_2.', ';
        $address .= (!empty($this->city) ? $this->city.', ' : '');
        $address .= (!empty($this->postal_code) ? $this->postal_code.', ' : '');
        $address .= (!empty($this->country) ? $this->country : '');
        return $address;
    }

    public function title(){
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function contact(){
        return $this->hasOne(CustomerContactInformation::class, 'customer_id', 'id');
    }
}
