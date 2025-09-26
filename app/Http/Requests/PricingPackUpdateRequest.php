<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PricingPackUpdateRequest extends FormRequest
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
            'title' => 'required',
            'subtitle' => 'required',
            'period' => 'required',
            'order' => 'required',
            'price' => 'sometimes|numeric',
            'stripe_plan' => 'required_unless:price,0',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'This field is required.',
            'subtitle.required' => 'This field is required.',
            'period.required' => 'This field is required.',
            'order.required' => 'This field is required.',
            'price.numeric' => 'Insert a valid Number.',
            'stripe_plan.required_unless' => 'This field is required.',
        ];
    }
}
