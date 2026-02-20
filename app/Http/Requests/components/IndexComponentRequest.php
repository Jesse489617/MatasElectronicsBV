<?php

namespace App\Http\Requests\components;

use Illuminate\Foundation\Http\FormRequest;

class IndexComponentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }
}
