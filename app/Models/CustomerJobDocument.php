<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CustomerJobDocument extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $appends = ['download_url'];

    protected $fillable = [
        'customer_id',
        'customer_job_id',
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
        if(!empty($this->current_file_name) && Storage::disk('public')->exists('customers/'.$this->customer_id.'/jobs/'.$this->customer_job_id.'/'.$this->current_file_name)):
            return Storage::disk('public')->url('customers/'.$this->customer_id.'/jobs/'.$this->customer_job_id.'/'.$this->current_file_name);
        else:
            return false;
        endif;
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function job(){
        return $this->belongsTo(CustomerJob::class, 'customer_job_id');
    }
}
