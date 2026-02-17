<?php

namespace App\Http\Requests\assemblies;

use App\Models\Component;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssemblyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'components' => 'required|array|min:1',
            'components.*' => [
                Rule::exists(Component::class, 'id'),
            ],
        ];
    }
}
