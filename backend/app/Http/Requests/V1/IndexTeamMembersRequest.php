<?php

namespace App\Http\Requests\V1;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexTeamMembersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filters' => ['sometimes', 'array'],
            'filters.group' => ['sometimes', 'string', Rule::in(TeamMember::GROUPS)],
        ];
    }
}
