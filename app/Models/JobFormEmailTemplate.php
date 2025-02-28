<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobFormEmailTemplate extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'job_form_id',
        'subject',
        'content',
        'cc_email_address',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];

    public function attachment(){
        return $this->hasMany(JobFormEmailTemplateAttachment::class, 'job_form_email_template_id', 'id');
    }
}
