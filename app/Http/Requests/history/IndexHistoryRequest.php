<?php

namespace App\Http\Requests\history;

use Illuminate\Foundation\Http\FormRequest;

class IndexHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
