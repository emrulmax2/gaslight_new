<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerJobStoreRequest extends FormRequest
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
            'customer_id' => 'required',
            'customer_property_id' => 'required|not_in:0',
            'job_calender_date' => 'nullable|date',
            'calendar_time_slot_id' => 'required_with:job_calender_date|nullable',
        ];
    }

    public function messages()
    {
        return [
            'customer_property_id.required' => 'Please select a property.',
            'customer_property_id.not_in' => 'Please select a property.',
            'job_calender_date.date' => 'Please enter a valid date.',
            'calendar_time_slot_id.required_with' => 'The slot field is required when appointment date is selected.',
        ];
    }
}
