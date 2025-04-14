<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobAddressStoreRequest extends FormRequest
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
            //'address_line_2' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
        ];
    }
}
