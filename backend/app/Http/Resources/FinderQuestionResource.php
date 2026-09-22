<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class FinderQuestionResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        $options = [];
        foreach ($this->options as $option) {
            $options[] = [
                'tag' => $option['tag'] ?? null,
                'icon' => $option['icon'] ?? null,
                'label' => $option['label'][$locale] ?? $option['label']['en'] ?? null,
                'description' => $option['description'][$locale] ?? $option['description']['en'] ?? null,
            ];
        }

        return [
            'id' => $this->id,
            'key' => $this->key,
            'question' => $this->getTranslation('question', $locale),
            'options' => $options,
        ];
    }
}
