<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExistingRecordDraft extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $fillable = [
        'customer_id',
        'customer_job_id',
        'job_form_id',
        'model_type',
        'model_id',

        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function job(){
        return $this->belongsTo(CustomerJob::class, 'customer_job_id');
    }

    public function form(){
        return $this->belongsTo(JobForm::class, 'job_form_id');
    }
    
    public function model(): MorphTo {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
