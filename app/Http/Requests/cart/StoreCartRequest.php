<?php

namespace App\Http\Requests\cart;

use App\Models\Assembly;
use App\Models\Component;
use App\Models\CustomAssembly;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCartRequest extends FormRequest
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
                'required_without_all:assembly_id,custom_assembly_id',
                'prohibited_with:assembly_id,custom_assembly_id',
                Rule::exists(Component::class, 'id'),
            ],
            'assembly_id' => [
                'nullable',
                'required_without_all:component_id,custom_assembly_id',
                'prohibited_with:component_id,custom_assembly_id',
                Rule::exists(Assembly::class, 'id'),
            ],
            'custom_assembly_id' => [
                'nullable',
                'required_without_all:component_id,assembly_id',
                'prohibited_with:component_id,assembly_id',
                Rule::exists(CustomAssembly::class, 'id'),
            ],
            'quantity' => ['required', 'integer', 'min:1'],

        ];
    }
}
