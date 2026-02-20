<?php

namespace App\Http\Requests\buy;

use App\Models\Assembly;
use App\Models\Component;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBuyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'component_id' => [
                'nullable',
                'required_without:assembly_id',
                Rule::exists(Component::class, 'id'),
            ],
            'assembly_id' => [
                'nullable',
                'required_without:component_id',
                Rule::exists(Assembly::class, 'id'),
            ],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
