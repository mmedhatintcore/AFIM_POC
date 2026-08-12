<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Fund extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'category_label', 'description'];

    protected $fillable = [
        'slug', 'name', 'category_label', 'group_key', 'order_channel', 'platforms',
        'risk_level', 'nav_price', 'daily_change', 'yield_1y', 'spark',
        'illustration', 'description', 'is_featured', 'is_published', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'platforms' => 'array',
            'spark' => 'array',
            'risk_level' => 'integer',
            'nav_price' => 'decimal:2',
            'daily_change' => 'decimal:2',
            'yield_1y' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'sort' => 'integer',
        ];
    }

    #[Scope]
    public function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    public function featured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    #[Scope]
    public function ordered(Builder $query): void
    {
        $query->orderBy('sort');
    }
}
