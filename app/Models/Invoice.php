<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes, HasFactory;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }

    protected $fillable = [
        'customer_id',
        'customer_job_id',
        'job_form_id',
        'invoice_number',
        'issued_date',
        'reference_no',
        'non_vat_invoice',
        'vat_number',
        'advance_amount',
        'payment_method_id',
        'advance_date',
        'notes',
        'payment_term',
        'status',
        
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

    public function method(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function items(){
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id')->orderBy('id', 'ASC');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
