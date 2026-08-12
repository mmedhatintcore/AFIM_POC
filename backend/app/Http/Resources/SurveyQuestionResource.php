<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class SurveyQuestionResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        $options = [];
        foreach ($this->options as $index => $option) {
            $options[] = [
                'index' => $index,
                'icon' => $option['icon'] ?? null,
                'label' => $option['label'][$locale] ?? $option['label']['en'] ?? null,
                'description' => $option['description'][$locale] ?? $option['description']['en'] ?? null,
                // Vote weights intentionally omitted — scoring is server-side.
            ];
        }

        return [
            'id' => $this->id,
            'key' => $this->key,
            'phase' => $this->getTranslation('phase', $locale),
            'question' => $this->getTranslation('question', $locale),
            'layout' => $this->layout,
            'options' => $options,
        ];
    }
}
