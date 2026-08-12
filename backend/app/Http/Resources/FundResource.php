<?php

namespace App\Http\Resources;

use App\Support\Localization\LocalizesNested;
use Illuminate\Http\Resources\Json\JsonResource;

final class FundResource extends JsonResource
{
    private const RISK_KEYS = [0 => 'risk_low', 1 => 'risk_medium', 2 => 'risk_high'];

    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->getTranslation('name', $locale),
            'category_label' => $this->getTranslation('category_label', $locale) ?: null,
            'group_key' => $this->group_key,
            'risk_level' => $this->risk_level,
            'risk_label' => $this->risk_level !== null ? __('messages.'.self::RISK_KEYS[$this->risk_level]) : null,
            'nav_price' => $this->nav_price,
            'daily_change' => $this->daily_change,
            'yield_1y' => $this->yield_1y,
            'spark' => $this->spark,
            'illustration' => $this->illustration,
            'order_channel' => $this->order_channel,
            'order_channel_label' => __('messages.channel_'.$this->order_channel),
            'how_to' => __('messages.how_to_'.$this->order_channel),
            'platforms' => LocalizesNested::localize($this->platforms, $locale) ?? [],
            'description' => $this->getTranslation('description', $locale) ?: null,
            'is_featured' => $this->is_featured,
        ];
    }
}
