<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyInfoRequest extends FormRequest
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
            'company_name' => 'required',
            'business_type' => ['required', 'string', 'max:255'],
            'company_registration' => ['required_if:business_type, Company'],
            'vat_number_check' => 'sometimes',
            'vat_number' => ['required_if:vat_number_check,1'],
        ];
    }
}
