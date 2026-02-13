<?php

namespace App\Http\Requests\history;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\UserAssembly;

class InvoiceHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function purchase(): UserAssembly
    {
        return UserAssembly::where('user_id', $this->user()->id)
            ->where('id', $this->route('id'))
            ->with('assembly.components')
            ->firstOrFail();
    }
}
