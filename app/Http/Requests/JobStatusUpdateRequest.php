<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobStatusUpdateRequest extends FormRequest
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
            'customer_job_status_id' => 'required'
        ];
    }

    public function messages(){
        return [
            'customer_job_status_id.required' => 'Please select an status.'
        ];
    }
}
