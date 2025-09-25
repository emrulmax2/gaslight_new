<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['customer_full_name','full_address', 'full_address_html', 'pdf_address', 'full_address_with_html'];
    
    protected $fillable = [
        'title_id',
        'full_name',
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

    public function getCustomerFullNameAttribute(){
        return $this->full_name;
    }

    public function getFullAddressAttribute(){
        $address = '';
        $address .= $this->address_line_1.' '.$this->address_line_2.', ';
        $address .= (!empty($this->city) ? $this->city.', ' : '');
        $address .= (!empty($this->postal_code) ? $this->postal_code : '');
        //$address .= (!empty($this->state) ? $this->state.', ' : '');
        //$address .= (!empty($this->country) ? $this->country : '');
        return $address;
    }

    public function getPdfAddressAttribute(){
        $address = '';
        $address .= $this->address_line_1.' '.$this->address_line_2.', ';
        $address .= (!empty($this->city) ? $this->city : '');
        //$address .= (!empty($this->postal_code) ? $this->postal_code.', ' : '');
        //$address .= (!empty($this->state) ? $this->state.' ' : '');
        //$address .= (!empty($this->country) ? $this->country : '');
        return $address;
    }

    public function getFullAddressHtmlAttribute(){
        $address = '';
        $address .= $this->address_line_1.' '.$this->address_line_2.',<br/> ';
        $address .= (!empty($this->city) ? $this->city.', ' : '');
        $address .= (!empty($this->postal_code) ? $this->postal_code : '');
        //$address .= (!empty($this->state) ? $this->state.',<br/> ' : '');
        //$address .= (!empty($this->country) ? $this->country : '');
        return $address;
    }

    public function getFullAddressWithHtmlAttribute(){
        $address = '';
        $address .= $this->address_line_1.' '.$this->address_line_2.',<br/> ';
        $address .= (!empty($this->city) ? $this->city.', ' : '');
        $address .= (!empty($this->postal_code) ? $this->postal_code.', ' : '');
        $address .= (!empty($this->state) ? $this->state.',<br/> ' : '');
        $address .= (!empty($this->country) ? $this->country : '');
        return $address;
    }

    public function title(){
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function contact(){
        return $this->hasOne(CustomerContactInformation::class, 'customer_id', 'id');
    }

    public function jobs(){
        return $this->hasMany(CustomerJob::class, 'customer_id', 'id');
    }

    public function properties(){
        return $this->hasMany(CustomerProperty::class, 'customer_id', 'id');
    }
}
