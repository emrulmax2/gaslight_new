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

    protected $appends = ['logo_url'];

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

}
