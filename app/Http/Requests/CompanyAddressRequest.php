<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyAddressRequest extends FormRequest
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
            'company_address_line_1' => 'required',
            'company_address_line_2' => 'required',
            'company_city' => 'required',
            'company_postal_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'company_address_line_1.required' => 'This field is required.',
            'company_address_line_2.required' => 'This field is required.',
            'company_city.required' => 'This field is required.',
            'company_postal_code.required' => 'This field is required.',
        ];
    }
}
