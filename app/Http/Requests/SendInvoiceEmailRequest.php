<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendInvoiceEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_email' => 'required',
            'invoice_id' => 'required',
            'subject' => 'required',
            'content' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'customer_email' => 'This field is required.',
            'invoice_id' => 'This field is required.',
            'subject' => 'This field is required.',
            'content' => 'This field is required.'
        ];
    }
}
