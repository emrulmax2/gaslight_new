<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes, SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }

    protected $appends = ['available_options'];

    protected $fillable = [
        'company_id',
        'customer_id',
        'customer_job_id',
        'job_form_id',
        'customer_property_id',
        'customer_property_occupant_id',
        'invoice_number',
        'issued_date',
        'expire_date',
        'status',
        'pay_status',
        
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function job(){
        return $this->belongsTo(CustomerJob::class, 'customer_job_id');
    }

    public function form(){
        return $this->belongsTo(JobForm::class, 'job_form_id');
    }

    public function property(){
        return $this->belongsTo(CustomerProperty::class, 'customer_property_id');
    }

    public function occupant(){
        return $this->belongsTo(CustomerPropertyOccupant::class, 'customer_property_occupant_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function options(){
        return $this->hasMany(InvoiceOption::class, 'invoice_id', 'id')->orderBy('id', 'asc');
    }

    public function getAvailableOptionsAttribute(){
        $options = [];
        if($this->options->count() > 0):
            foreach($this->options as $option):
                $options[$option->name] = $option->value;
            endforeach;
        endif;

        return (object) $options;
    }
}
