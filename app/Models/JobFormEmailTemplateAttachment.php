<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class JobFormEmailTemplateAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['download_url'];
    
    protected $fillable = [
        'job_form_email_template_id',
        'display_file_name',
        'current_file_name',
        'doc_type',
        'disk_type',
        'path',
        
        'created_by',
        'updated_by'
    ];


    protected $dates = ['deleted_at'];

    public function getDownloadUrlAttribute(){
        if(!empty($this->current_file_name) && Storage::disk('public')->exists('template_attachments/'.$this->job_form_email_template_id.'/'.$this->current_file_name)):
            return Storage::disk('public')->url('template_attachments/'.$this->job_form_email_template_id.'/'.$this->current_file_name);
        else:
            return false;
        endif;
    }

    public function template(){
        return $this->belongsTo(JobFormEmailTemplate::class, 'job_form_email_template_id');
    }
}
