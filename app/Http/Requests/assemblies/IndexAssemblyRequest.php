<?php

namespace App\Http\Requests\assemblies;

use Illuminate\Foundation\Http\FormRequest;

class IndexAssemblyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
        ];
    }
}
