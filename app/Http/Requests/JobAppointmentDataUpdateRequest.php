<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobAppointmentDataUpdateRequest extends FormRequest
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
            'job_calender_date' => 'nullable|date',
            'calendar_time_slot_id' => 'required_with:job_calender_date|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'job_calender_date.date' => 'Please enter a valid date.',
            'calendar_time_slot_id.required_with' => 'The slot field is required when appointment date is selected.',
        ];
    }
}
