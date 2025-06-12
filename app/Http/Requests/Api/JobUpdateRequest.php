<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class JobUpdateRequest extends FormRequest
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
            'description' => 'sometimes|nullable|string|max:255',
            'details' => 'sometimes|nullable|string',
            'customer_job_priority_id' => 'sometimes|nullable|integer|exists:customer_job_priorities,id',
            'due_date' => 'sometimes|nullable|date_format:Y-m-d',
            'customer_job_status_id' => 'sometimes|nullable|integer|exists:customer_job_statuses,id',
            'reference_no' => 'sometimes|nullable|string|max:255',
            'estimated_amount' => 'sometimes|nullable|numeric|min:0',
            'job_calender_date' => 'sometimes|nullable|date_format:Y-m-d',
            'calendar_time_slot_id' => 'sometimes|nullable|required_with:job_calender_date|integer|exists:calendar_time_slots,id',
        ];
    }

    public function messages(): array
    {
        return [
            'due_date.date_format' => 'Please enter a valid due date (YYYY-MM-DD).',
            'job_calender_date.date_format' => 'Please enter a valid appointment date (YYYY-MM-DD).',
            'calendar_time_slot_id.required_with' => 'The calendar time slot is required when an appointment date is provided.',
            'customer_job_priority_id.exists' => 'Invalid job priority selected.',
            'customer_job_status_id.exists' => 'Invalid job status selected.',
            'calendar_time_slot_id.exists' => 'Invalid calendar time slot selected.',
            'estimated_amount.numeric' => 'Estimated amount must be a number.',
            'estimated_amount.min' => 'Estimated amount cannot be negative.',
        ];
    }
}
