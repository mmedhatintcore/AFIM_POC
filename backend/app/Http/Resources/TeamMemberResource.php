<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class TeamMemberResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'group' => $this->group,
            'name' => $this->getTranslation('name', $locale),
            'role' => $this->getTranslation('role', $locale),
        ];
    }
}
