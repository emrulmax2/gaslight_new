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
        'company_id',
        'title_id',
        'full_name',
        'company_name',
        'vat_no',
        'note',
        'auto_reminder',

        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];

    public function getCustomerFullNameAttribute(){
        return $this->full_name;
    }

    public function address(){
        return $this->hasOne(CustomerProperty::class, 'customer_id', 'id')->where('is_primary', 1);
    }

    public function getFullAddressAttribute(){
        $address = '';
        $address .= isset($this->address->address_line_1) && !empty($this->address->address_line_1) ? $this->address->address_line_1.' ' : '';
        $address .= isset($this->address->address_line_2) && !empty($this->address->address_line_2) ? $this->address->address_line_2.', ' : '';
        $address .= (isset($this->address->city) && !empty($this->address->city) ? $this->address->city.', ' : '');
        $address .= (isset($this->address->postal_code) && !empty($this->address->postal_code) ? $this->address->postal_code : '');
        return $address;
    }

    public function getPdfAddressAttribute(){
        $address = '';
        $address .= isset($this->address->address_line_1) && !empty($this->address->address_line_1) ? $this->address->address_line_1.' ' : '';
        $address .= isset($this->address->address_line_2) && !empty($this->address->address_line_2) ? $this->address->address_line_2.', ' : '';
        $address .= (isset($this->address->city) && !empty($this->address->city) ? $this->address->city : '');
        return $address;
    }

    public function getFullAddressHtmlAttribute(){
        $address = '';
        $address .= isset($this->address->address_line_1) && !empty($this->address->address_line_1) ? $this->address->address_line_1.' ' : '';
        $address .= isset($this->address->address_line_2) && !empty($this->address->address_line_2) ? $this->address->address_line_2.',<br/>' : '';
        $address .= (isset($this->address->city) && !empty($this->address->city) ? $this->address->city.', ' : '');
        $address .= (isset($this->address->postal_code) && !empty($this->address->postal_code) ? $this->address->postal_code : '');
        //$address .= (!empty($this->state) ? $this->state.',<br/> ' : '');
        //$address .= (!empty($this->country) ? $this->country : '');
        return $address;
    }

    public function getFullAddressWithHtmlAttribute(){
        $address = '';
        $address .= isset($this->address->address_line_1) && !empty($this->address->address_line_1) ? $this->address->address_line_1.' ' : '';
        $address .= isset($this->address->address_line_2) && !empty($this->address->address_line_2) ? $this->address->address_line_2.',<br/>' : '';
        $address .= (isset($this->address->city) && !empty($this->address->city) ? $this->address->city.', ' : '');
        $address .= (isset($this->address->postal_code) && !empty($this->address->postal_code) ? $this->address->postal_code.', ' : '');
        $address .= (isset($this->address->state) && !empty($this->address->state) ? $this->address->state.',<br/> ' : '');
        $address .= (isset($this->address->country) && !empty($this->address->country) ? $this->address->country : '');
        return $address;
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
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
