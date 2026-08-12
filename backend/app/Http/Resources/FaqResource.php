<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class FaqResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'question' => $this->getTranslation('question', $locale),
            'answer' => $this->getTranslation('answer', $locale),
            'action_type' => $this->action_type,
            'action_label' => $this->getTranslation('action_label', $locale) ?: null,
        ];
    }
}
