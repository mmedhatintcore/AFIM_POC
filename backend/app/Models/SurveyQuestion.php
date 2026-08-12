<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SurveyQuestion extends Model
{
    use HasTranslations;

    public array $translatable = ['phase', 'question'];

    protected $fillable = ['key', 'phase', 'question', 'layout', 'options', 'sort', 'is_active'];

    protected function casts(): array
    {
        return ['options' => 'array', 'is_active' => 'boolean', 'sort' => 'integer'];
    }

    #[Scope]
    public function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    public function ordered(Builder $query): void
    {
        $query->orderBy('sort');
    }
}
