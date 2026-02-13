<?php

namespace App\Http\Requests\components;

use Illuminate\Foundation\Http\FormRequest;

class BuyComponentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'component_id' => 'required|exists:components,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'component_id.required' => 'Component ID is required.',
            'component_id.exists' => 'The selected component does not exist.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
