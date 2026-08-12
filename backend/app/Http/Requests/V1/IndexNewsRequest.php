<?php

namespace App\Http\Requests\V1;

use App\Models\NewsPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filters' => ['sometimes', 'array'],
            'filters.type' => ['sometimes', 'string', Rule::in(NewsPost::TYPES)],
            'search' => ['sometimes', 'nullable', 'string', 'max:190'],
            'sort' => ['sometimes', 'string', Rule::in(['published_at', '-published_at'])],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ];
    }
}
