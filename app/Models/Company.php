<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['pdf_address', 'logo_url', 'full_address', 'full_address_html'];

    protected $fillable = [
        'id',
        'user_id',
        'company_name',
        'company_email',
        'company_phone',
        'vat_number',
        'display_company_name',
        'gas_safe_registration_no',
        'registration_no',
        'registration_body_for',
        'registration_body_for_legionella',
        'registration_body_no_for_legionella',
        'company_web_site',
        'company_tagline',
        'company_address_line_1',
        'company_address_line_2',
        'company_postal_code',
        'company_state',
        'company_city',
        'company_country',
        'company_logo',
        'company_registration',
        'business_type',
    ];

    public function getLogoUrlAttribute(){
        if(!empty($this->company_logo) && Storage::disk('public')->exists('companies/'.$this->id.'/'.$this->company_logo)):
            return Storage::disk('public')->url('companies/'.$this->id.'/'.$this->company_logo);
        else:
            return false;
        endif;
    }

    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'company_staff', 'company_id', 'staff_id');
    }

    public function companyBankDetails()
    {
        return $this->hasOne(CompanyBankDetails::class, 'Company_id', 'id'); 
    }

    public function getFullAddressAttribute(){
        $address = '';
        $address .= $this->company_address_line_1.' '.$this->company_address_line_2.', ';
        $address .= (!empty($this->company_city) ? $this->company_city.', ' : '');
        $address .= (!empty($this->company_postal_code) ? $this->company_postal_code : '');
        //$address .= (!empty($this->company_state) ? $this->company_state.', ' : '');
        //$address .= (!empty($this->company_country) ? $this->company_country : '');
        return $address;
    }

    public function getFullAddressHtmlAttribute(){
        $address = '';
        $address .= $this->company_address_line_1.' '.$this->company_address_line_2.',<br/> ';
        $address .= (!empty($this->company_city) ? $this->company_city.', ' : '');
        $address .= (!empty($this->company_postal_code) ? $this->company_postal_code : '');
        //$address .= (!empty($this->company_state) ? $this->company_state.', ' : '');
        //$address .= (!empty($this->company_country) ? $this->company_country : '');
        return $address;
    }

    public function getPdfAddressAttribute(){
        $address = '';
        $address .= $this->company_address_line_1.' '.$this->company_address_line_2.', ';
        $address .= (!empty($this->company_city) ? $this->company_city.', ' : '');
        $address .= (!empty($this->company_postal_code) ? $this->company_postal_code : '');
        //$address .= (!empty($this->company_state) ? $this->company_state.' ' : '');
        //$address .= (!empty($this->company_country) ? $this->company_country : '');
        return $address;
    }

    public function bank()
    {
        return $this->hasOne(CompanyBankDetails::class, 'Company_id', 'id'); 
    }

}
