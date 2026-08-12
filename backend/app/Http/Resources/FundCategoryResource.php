<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class FundCategoryResource extends JsonResource
{
    private const RISK_KEYS = [0 => 'risk_low', 1 => 'risk_medium', 2 => 'risk_high'];

    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'key' => $this->key,
            'name' => $this->getTranslation('name', $locale),
            'description' => $this->getTranslation('description', $locale),
            'risk_level' => $this->risk_level,
            'risk_label' => __('messages.'.self::RISK_KEYS[$this->risk_level]),
            'fund_group' => $this->fund_group,
            'illustration' => $this->illustration,
        ];
    }
}
