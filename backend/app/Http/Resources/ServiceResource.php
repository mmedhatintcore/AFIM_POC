<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'key' => $this->key,
            'slug' => $this->slug,
            'name' => $this->getTranslation('name', $locale),
            'description' => $this->getTranslation('description', $locale),
            'body' => $this->getTranslation('body', $locale) ?: null,
            'icon' => $this->icon,
            'sort' => $this->sort,
        ];
    }
}
