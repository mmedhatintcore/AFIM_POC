<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class FinderQuestionResource extends JsonResource
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
                // Unlike the Survey's vote weights, these are exposed on
                // purpose — the finder's result routing runs client-side.
                'votes' => [
                    'portfolio' => (int) ($option['votes']['portfolio'] ?? 0),
                    'liquidity' => (int) ($option['votes']['liquidity'] ?? 0),
                    'subscription' => (int) ($option['votes']['subscription'] ?? 0),
                    'funds' => (int) ($option['votes']['funds'] ?? 0),
                ],
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
