<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyAddressUpdateRequest extends FormRequest
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
            'address_line_1' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'address_line_1.required' => 'This field is required.',
            'address_line_2.required' => 'This field is required.',
            'city.required' => 'This field is required.',
            'postal_code.required' => 'This field is required.',
        ];
    }
}
