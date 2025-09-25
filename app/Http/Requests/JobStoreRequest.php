<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobStoreRequest extends FormRequest
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
            'customer_id' => 'required|numeric|gt:0',
            'customer_property_id' => 'required|numeric|gt:0',
            'job_calender_date' => 'nullable|date',
            'calendar_time_slot_id' => 'required_with:job_calender_date|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required.',
            'customer_id.gt' => 'Customer is required.',
            'customer_property_id.required' => 'Customer property is required.',
            'customer_property_id.gt' => 'Customer property is required.',
            'job_calender_date.date' => 'Please enter a valid date.',
            'calendar_time_slot_id.required_with' => 'The slot field is required.',
        ];
    }
}
