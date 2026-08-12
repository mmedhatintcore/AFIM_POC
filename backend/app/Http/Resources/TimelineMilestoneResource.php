<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class TimelineMilestoneResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'year' => $this->year,
            'title' => $this->getTranslation('title', $locale),
            'body' => $this->getTranslation('body', $locale),
        ];
    }
}
