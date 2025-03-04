<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
            //if password is not empty, then it should be at least 8 characters long and confirmation should match
            'password' => 'nullable|min:8|confirmed',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            
        ];
    }
}
