<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class NewsPostResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'type' => $this->type,
            'type_label' => __('messages.type_'.$this->type),
            'source' => $this->source,
            'title' => $this->getTranslation('title', $locale),
            'excerpt' => $this->getTranslation('excerpt', $locale),
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
