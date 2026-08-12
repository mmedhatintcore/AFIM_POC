<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexFundsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filters' => ['sometimes', 'array'],
            'filters.is_featured' => ['sometimes', 'boolean'],
            'filters.group_key' => ['sometimes', 'string', Rule::in(['mm', 'imm', 'mixed', 'balanced', 'metals', 'equity', 'iequity'])],
            'search' => ['sometimes', 'nullable', 'string', 'max:190'],
        ];
    }
}
