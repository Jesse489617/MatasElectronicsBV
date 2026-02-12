<?php

namespace App\Http\Requests\assemblies;

use Illuminate\Foundation\Http\FormRequest;

class BuyAssemblyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assembly_id' => 'required|exists:assemblies,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
