<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class JobForm extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot() {
        parent::boot();

        static::creating(function ($jobForm) {
            $jobForm->slug = Str::slug($jobForm->name);
        });
    }
    
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'active',
        'roder',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];

    public function childs(){
        return $this->hasMany(JobForm::class, 'parent_id', 'id');
    }
}
