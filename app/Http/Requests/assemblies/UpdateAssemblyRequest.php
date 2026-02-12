<?php

namespace App\Http\Requests\assemblies;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssemblyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'components' => 'required|array|min:1',
            'components.*' => 'exists:components,id',
        ];
    }
}
