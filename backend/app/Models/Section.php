<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Section extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'subtitle', 'body'];

    protected $fillable = ['key', 'is_enabled', 'title', 'subtitle', 'body', 'items', 'cta', 'extra'];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'items' => 'array',
            'cta' => 'array',
            'extra' => 'array',
        ];
    }

    #[Scope]
    public function enabled(Builder $query): void
    {
        $query->where('is_enabled', true);
    }
}
