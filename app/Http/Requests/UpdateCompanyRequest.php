<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:255'],
            'company_phone' => ['required', 'string', 'max:255'],
            'company_address_line_1' => ['required', 'string', 'max:255'],
            'company_postal_code' => ['required', 'string', 'max:255'],
            'company_city' => ['required', 'string', 'max:255'],
            'company_country' => ['required', 'string', 'max:255'],
            'company_registration' => ['required_if:business_type,Company'],//, 'string', 'max:255'
            'business_type' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:255'],
            'company_email' => ['nullable', 'string', 'max:255'],
            'company_address_line_1' => ['nullable', 'string', 'max:255'],
            'company_postal_code' => ['nullable', 'string', 'max:255'],
            'company_state' => ['nullable', 'string', 'max:255'],
            'company_city' => ['nullable', 'string', 'max:255'],
            'company_country' => ['nullable', 'string', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:255'],
            'vat_number' => ['nullable', 'string', 'max:255'],
            'display_company_name' => ['nullable'],
            'gas_safe_registration_no' => ['nullable'],
            'registration_no' => ['nullable'],
            'registration_body_for' => ['nullable'],
            'registration_body_for_legionella' => ['nullable'],
            'registration_body_no_for_legionella' => ['nullable'],
            'building_or_no' => ['nullable'],
            'company_web_site' => ['nullable'],
            'company_tagline' => ['nullable'],
        ];
    }
}
