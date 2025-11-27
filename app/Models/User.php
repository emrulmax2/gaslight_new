<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;
use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;

class User extends Authenticatable implements MustVerifyEmail, CanBeSigned
{
    use HasFactory, Notifiable, Impersonate,RequiresSignature, HasApiTokens;

    protected $appends = ['photo_url', 'photo_url_api'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'active',
        'photo',
        'password',
        'google_id',
        'gas_safe_id_card',
        'oil_registration_number',
        'installer_ref_no',
        'parent_id',
        'role',
        'mobile',
        'first_login',
        'max_job_per_slot'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function getPhotoUrlAttribute()
    {
        if ($this->photo !== null && Storage::disk('public')->exists('users/'.$this->id.'/'.$this->photo)) {
            return Storage::disk('public')->url('users/'.$this->id.'/'.$this->photo);
        } else {
            return Vite::asset('resources/images/placeholders/200x200.jpg');
        }
    }

    public function getPhotoUrlApiAttribute()
    {
        if ($this->photo !== null && Storage::disk('public')->exists('users/'.$this->id.'/'.$this->photo)) {
            return Storage::disk('public')->url('users/'.$this->id.'/'.$this->photo);
        } else {
            return '';
        }
    }

    //company relationship
    public function company(){
        return $this->hasOne(Company::class);
    }

    public function companies(){
        return $this->belongsToMany(Company::class, 'company_staff', 'user_id', 'company_id');
    }

    public function referral()
    {
        return $this->hasOne(UserReferralCode::class, 'user_id');
    }

    public function userpackage()
    {
        return $this->hasOne(UserPricingPackage::class, 'user_id')->latestOfMany();//->where('active', 1)
    }

    public function getSubscribedAttribute()
    {
        if ( (!isset($this->userpackage->active) || $this->userpackage->active == 0) || (empty($this->userpackage->end) || date('Y-m-d', strtotime($this->userpackage->end)) < date('Y-m-d')) ) {
            return false;
        }

        return true;
    }

    
}
