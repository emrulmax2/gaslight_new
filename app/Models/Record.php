<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;

class Record extends Model implements CanBeSigned
{
    use SoftDeletes, HasFactory, RequiresSignature;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }

    protected $appends = ['available_options', 'has_invoice', 'invoice_url'];

    protected $fillable = [
        'company_id',
        'customer_id',
        'customer_job_id',
        'job_form_id',
        'customer_property_id',
        'customer_property_occupant_id',
        'certificate_number',
        'inspection_date',
        'next_inspection_date',
        'received_by',
        'relation_id',
        'status',
        'linked_id',
        
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

    public function relation(){
        return $this->belongsTo(Relation::class, 'relation_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function options(){
        return $this->hasMany(RecordOption::class, 'record_id', 'id')->orderBy('id', 'asc');
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

    public function getHasInvoiceAttribute(){
        $record = Record::where('job_form_id', 4)->where('linked_id', $this->id)->get()->first();
        return (isset($record->id) && $record->id > 0 ? true : false);
    }

    public function getInvoiceUrlAttribute(){
        $record = Record::where('job_form_id', 4)->where('linked_id', $this->id)->get()->first();
        return (isset($record->id) && $record->id > 0 ? route('records.show', $record->id) : 'javascript:void(0);');
    }
}
