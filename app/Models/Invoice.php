<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }

    protected $appends = ['available_options', 'invoice_total', 'invoice_due', 'invoice_paid'];

    protected $fillable = [
        'company_id',
        'customer_id',
        'customer_job_id',
        'job_form_id',
        'customer_property_id',
        'invoice_number',
        'issued_date',
        'expire_date',
        'status',
        'pay_status',
        'invoice_cancel_reason_id',
        'cancel_reason_note',
        'cancelled_by',
        'cancelled_at',
        
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

    public function payments(){
        return $this->hasMany(InvoicePayment::class, 'invoice_id', 'id')->orderBy('id', 'asc');
    }

    public function getInvoiceTotalAttribute(){
        $invoiceItems = isset($this->available_options->invoiceItems) && !empty($this->available_options->invoiceItems) ? $this->available_options->invoiceItems : [];
        $invoiceDiscounts = isset($this->available_options->invoiceDiscounts) && !empty($this->available_options->invoiceDiscounts) ? $this->available_options->invoiceDiscounts : [];
        $invoiceAdvance = isset($this->available_options->invoiceAdvance) && !empty($this->available_options->invoiceAdvance) ? $this->available_options->invoiceAdvance : [];

        $SUBTOTAL = 0;
        $VATTOTAL = 0;
        $TOTAL = 0;
        $DUE = 0;
        $DISCOUNTTOTAL = 0;
        $DISCOUNTVATTOTAL = 0;
        $ADVANCEAMOUNT = (isset($invoiceAdvance->advance_amount) && $invoiceAdvance->advance_amount > 0 ? $invoiceAdvance->advance_amount : 0);

        if(!empty($invoiceItems)):
            foreach($invoiceItems as $item):
                $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                $unitPrice = (!empty($item->price) && $item->price > 0 ? $item->price : 0);
                $vatRate = (!empty($item->vat) && $item->vat > 0 ? $item->vat : 0);
                $vatAmount = ($unitPrice * $vatRate) / 100;
                $lineTotal = (isset($invoiceExtra->non_vat_invoice) && $invoiceExtra->non_vat_invoice != 1 ? ($unitPrice * $units) + $vatAmount : ($unitPrice * $units));
                
                $SUBTOTAL += ($unitPrice * $units);
                $VATTOTAL += $vatAmount;
            endforeach;
        endif;

        $DISCOUNTUNITPRICE = (isset($invoiceDiscounts->amount) ? $invoiceDiscounts->amount : 0);
        $DISCOUNTTOTAL += $DISCOUNTUNITPRICE;
        $TOTAL = (isset($invoiceExtra->non_vat_invoice) && $invoiceExtra->non_vat_invoice != 1 ? $SUBTOTAL + $VATTOTAL : $SUBTOTAL) - $DISCOUNTTOTAL;
        
        $INVOICETOTAL = $TOTAL - $ADVANCEAMOUNT;

        return $INVOICETOTAL;
    }

    public function getInvoiceDueAttribute(){
        $invoicePayment = ($this->payments->count() > 0 ? $this->payments->sum('amount') : 0);
        $invoiceItems = isset($this->available_options->invoiceItems) && !empty($this->available_options->invoiceItems) ? $this->available_options->invoiceItems : [];
        $invoiceDiscounts = isset($this->available_options->invoiceDiscounts) && !empty($this->available_options->invoiceDiscounts) ? $this->available_options->invoiceDiscounts : [];
        $invoiceAdvance = isset($this->available_options->invoiceAdvance) && !empty($this->available_options->invoiceAdvance) ? $this->available_options->invoiceAdvance : [];

        $SUBTOTAL = 0;
        $VATTOTAL = 0;
        $TOTAL = 0;
        $DUE = 0;
        $DISCOUNTTOTAL = 0;
        $DISCOUNTVATTOTAL = 0;
        $ADVANCEAMOUNT = (isset($invoiceAdvance->advance_amount) && $invoiceAdvance->advance_amount > 0 ? $invoiceAdvance->advance_amount : 0);

        if(!empty($invoiceItems)):
            foreach($invoiceItems as $item):
                $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                $unitPrice = (!empty($item->price) && $item->price > 0 ? $item->price : 0);
                $vatRate = (!empty($item->vat) && $item->vat > 0 ? $item->vat : 0);
                $vatAmount = ($unitPrice * $vatRate) / 100;
                $lineTotal = (isset($invoiceExtra->non_vat_invoice) && $invoiceExtra->non_vat_invoice != 1 ? ($unitPrice * $units) + $vatAmount : ($unitPrice * $units));
                
                $SUBTOTAL += ($unitPrice * $units);
                $VATTOTAL += $vatAmount;
            endforeach;
        endif;

        $DISCOUNTUNITPRICE = (isset($invoiceDiscounts->amount) ? $invoiceDiscounts->amount : 0);
        $DISCOUNTTOTAL += $DISCOUNTUNITPRICE;
        $TOTAL = (isset($invoiceExtra->non_vat_invoice) && $invoiceExtra->non_vat_invoice != 1 ? $SUBTOTAL + $VATTOTAL : $SUBTOTAL) - $DISCOUNTTOTAL;
        
        $INVOICEDUE = $TOTAL - $ADVANCEAMOUNT - $invoicePayment;

        return $INVOICEDUE;
    }

    public function getInvoicePaidAttribute(){
        $invoicePayment = ($this->payments->count() > 0 ? $this->payments->sum('amount') : 0);
        $invoiceAdvance = isset($this->available_options->invoiceAdvance) && !empty($this->available_options->invoiceAdvance) ? $this->available_options->invoiceAdvance : [];
        $ADVANCEAMOUNT = (isset($invoiceAdvance->advance_amount) && $invoiceAdvance->advance_amount > 0 ? $invoiceAdvance->advance_amount : 0);

        $INVOICEPAID = $ADVANCEAMOUNT + $invoicePayment;

        return $INVOICEPAID;
    }
}
