<?php

namespace App\Http\Resources;

use App\Support\Localization\LocalizesNested;
use Illuminate\Http\Resources\Json\JsonResource;

final class SectionResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'key' => $this->key,
            'is_enabled' => $this->is_enabled,
            'title' => $this->getTranslation('title', $locale) ?: null,
            'subtitle' => $this->getTranslation('subtitle', $locale) ?: null,
            'body' => $this->getTranslation('body', $locale) ?: null,
            'items' => LocalizesNested::localize($this->items, $locale),
            'cta' => LocalizesNested::localize($this->cta, $locale),
            'extra' => LocalizesNested::localize($this->extra, $locale),
        ];
    }
}
