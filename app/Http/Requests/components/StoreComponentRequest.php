<?php

namespace App\Http\Requests\components;

use Illuminate\Foundation\Http\FormRequest;

class StoreComponentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'manufacturer_id' => 'required|integer|exists:manufacturers,id',
            'type' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
        ];
    }
}
