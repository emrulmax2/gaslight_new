<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;
    protected $guarded = ['id'];


    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'company_staff', 'company_id', 'staff_id');
    }
}
