<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected static function booted(){
        static::creating(function ($thisModel) {
            $thisModel->created_by = auth()->user()->id;
        });
    }

    protected $appends = ['available_options', 'quote_total', 'email_template'];

    protected $fillable = [
        'company_id',
        'customer_id',
        'billing_address_id',
        'customer_job_id',
        'job_form_id',
        'customer_property_id',
        'quote_number',
        'issued_date',
        'expire_date',
        'status',
        
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

    public function billing(){
        return $this->belongsTo(CustomerProperty::class, 'billing_address_id');
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
        return $this->hasMany(QuoteOption::class, 'quote_id', 'id')->orderBy('id', 'asc');
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

    public function getQuoteTotalAttribute(){
        $quoteItems = isset($this->available_options->quoteItems) && !empty($this->available_options->quoteItems) ? $this->available_options->quoteItems : [];
        $quoteDiscounts = isset($this->available_options->quoteDiscounts) && !empty($this->available_options->quoteDiscounts) ? $this->available_options->quoteDiscounts : [];
        $quoteExtra = isset($this->available_options->quoteExtra) && !empty($this->available_options->quoteExtra) ? $this->available_options->quoteExtra : [];

        $SUBTOTAL = 0;
        $VATTOTAL = 0;
        $TOTAL = 0;
        $DUE = 0;
        $DISCOUNTTOTAL = 0;
        $DISCOUNTVATTOTAL = 0;
        if(!empty($quoteItems)):
            foreach($quoteItems as $item):
                $units = (!empty($item->units) && $item->units > 0 ? $item->units : 1);
                $unitPrice = (!empty($item->price) && $item->price > 0 ? $item->price : 0);
                $vatRate = (!empty($item->vat) && $item->vat > 0 ? $item->vat : 0);
                $vatAmount = ($unitPrice * $vatRate) / 100;
                $lineTotal = (isset($quoteExtra->non_vat_quote) && $quoteExtra->non_vat_quote != 1 ? ($unitPrice * $units) + $vatAmount : ($unitPrice * $units));
                
                $SUBTOTAL += ($unitPrice * $units);
                $VATTOTAL += $vatAmount;
            endforeach;
        endif;

        $DISCOUNTUNITPRICE = (isset($quoteDiscounts->amount) ? $quoteDiscounts->amount : 0);
        $DISCOUNTTOTAL += $DISCOUNTUNITPRICE;
        $TOTAL = (isset($quoteExtra->non_vat_quote) && $quoteExtra->non_vat_quote != 1 ? $SUBTOTAL + $VATTOTAL : $SUBTOTAL) - $DISCOUNTTOTAL;
        
        $QUOTETOTAL = $TOTAL;

        return $QUOTETOTAL;
    }

    public function getEmailTemplateAttribute(){
        $template = JobFormEmailTemplate::with('attachment')->where('user_id', $this->created_by)->where('job_form_id', $this->job_form_id)->get()->first();
        $subject = (isset($template->subject) && !empty($template->subject) ? $template->subject : null);
        $content = (isset($template->content) && !empty($template->content) ? $template->content : null);
        $cc_email_address = (isset($template->cc_email_address) && !empty($template->cc_email_address) ? $template->cc_email_address : null);

        $companyName = $this->user->companies->pluck('company_name')->first();
        $shortcodes = [
            ':customername'         => $this->customer->full_name ?? '',
            ':customercompany'      => $this->customer->company_name ?? '',
            ':jobref'               => $this->job->reference_no ?? '',
            ':jobbuilding'          => isset($this->job->property->address_line_1) && !empty($this->job->property->address_line_1) ? $this->job->property->address_line_1 : '',
            ':jobstreet'            => isset($this->job->property->address_line_2) && !empty($this->job->property->address_line_1) ? $this->job->property->address_line_2 : '',
            ':jobregion'            => isset($this->job->property->state) && !empty($this->job->property->state) ? $this->job->property->state : '',
            ':jobpostcode'          => isset($this->job->property->postal_code) && !empty($this->job->property->postal_code) ? $this->job->property->postal_code : '',
            ':jobtown'              => isset($this->job->property->city) && !empty($this->job->property->city) ? $this->job->property->city : '',
            ':propertyaddress'      => isset($this->property->full_address) && !empty($this->property->full_address) ? $this->property->full_address : '',
            ':contactphone'         => isset($this->user->mobile) && !empty($this->user->mobile) ? $this->user->mobile : '',
            ':companyname'          => $companyName ?? '',
            ':engineername'         => $this->user->name ?? '',
            ':eventdate'            => isset($this->job->calendar->date) && !empty($this->job->calendar->date) ? date('d-m-Y', strtotime($this->job->calendar->date)) : '',
            ':eventtime'            => (isset($this->job->calendar->slot->start) && !empty($this->job->calendar->slot->start) ? date('H:i', strtotime($this->job->calendar->slot->start)) : '').(isset($this->job->calendar->slot->end) && !empty($this->job->calendar->slot->end) ? ' - '.date('H:i', strtotime($this->job->calendar->slot->end)) : ''),
            // Add more shortcodes as needed
        ];

        // Replace shortcodes in subject and content
        $subject = str_replace(array_keys($shortcodes), array_values($shortcodes), $subject);
        $content = str_replace(array_keys($shortcodes), array_values($shortcodes), $content);

        $attachmentFiles = [];
        if(isset($template->attachment) && $template->attachment->count() > 0):
            $i = 1;
            foreach($template->attachment as $attachment):
                if(isset($attachment->download_url) && !empty($attachment->download_url)):
                    $attachmentFiles[$i] = [
                        "pathinfo" => 'template_attachments/'.$template->id.'/'.$attachment->current_file_name,
                        "nameinfo" => $attachment->current_file_name,
                        "mimeinfo" => $attachment->doc_type,
                        "disk" => 'public'
                    ];
                    $i++;
                endif;
            endforeach;
        endif;

        return (object)[
            'subject' => $subject,
            'content' => $content,
            'attachmentFiles' => $attachmentFiles,
            'cc_email_address' => $cc_email_address,
        ];
    }
}
