<?php

namespace App\Http\Resources;

use App\Support\Localization\LocalizesNested;
use Illuminate\Http\Resources\Json\JsonResource;

final class FundResource extends JsonResource
{
    public const ALLOCATION_TYPES = ['tbills', 'bonds', 'deposits', 'ics', 'cash', 'equity', 'other'];

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
            'currency' => $this->currency,
            'daily_change' => $this->daily_change,
            'yield_1y' => $this->yield_1y,
            'return_1m' => $this->return_1m,
            'inception_date' => $this->inception_date?->toDateString(),
            'spark' => $this->spark,
            'price_date' => $this->price_date?->toDateString(),
            'performance' => [
                '1m' => $this->return_1m,
                'ytd' => $this->yield_1y,
                '1y' => $this->return_1y,
                '3y' => $this->return_3y,
                '5y' => $this->return_5y,
                'since_inception' => $this->return_since_inception,
            ],
            'asset_allocation' => collect($this->asset_allocation ?? [])
                ->filter(fn ($row) => is_array($row) && isset($row['type'], $row['percent']))
                ->map(fn (array $row) => [
                    'type' => $row['type'],
                    'label' => __('messages.allocation_'.$row['type']),
                    'percent' => (float) $row['percent'],
                ])
                ->values()
                ->all(),
            'dividends' => [
                'ytd' => $this->dividends_ytd,
                'history' => collect($this->dividends ?? [])
                    ->filter(fn ($row) => is_array($row) && isset($row['date'], $row['amount']))
                    ->sortByDesc('date')
                    ->map(fn (array $row) => ['date' => $row['date'], 'amount' => (float) $row['amount']])
                    ->values()
                    ->all(),
            ],
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
