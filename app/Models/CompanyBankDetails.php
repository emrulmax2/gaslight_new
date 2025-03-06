<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyBankDetails extends Model
{
    protected $table = 'company_bank_details';


    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'Company_id',
        'bank_name',
        'name_on_account',
        'sort_code',
        'account_number',
        'payment_term',
   
    ];
}
