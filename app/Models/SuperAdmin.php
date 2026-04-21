<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use Lab404\Impersonate\Models\Impersonate;

class SuperAdmin extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Impersonate;

    protected $appends = ['photo_url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'mobile',
        'status',
        'role',
        'photo',
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

    //company relationship
    public function company()
    {
        return $this->hasOne(Company::class);
    }


    public function getPhotoUrlAttribute(){
        if ($this->photo !== null && Storage::disk('public')->exists('super-admins/'.$this->photo)) {
            return Storage::disk('public')->url('super-admins/'.$this->photo);
        } else {
            return Vite::asset('resources/images/placeholders/200x200.jpg');
        }
    }

    
}
