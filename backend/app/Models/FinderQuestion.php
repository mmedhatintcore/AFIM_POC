<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FinderQuestion extends Model
{
    use HasTranslations;

    public array $translatable = ['question'];

    protected $fillable = ['key', 'question', 'options', 'sort'];

    protected function casts(): array
    {
        return ['options' => 'array', 'sort' => 'integer'];
    }

    #[Scope]
    public function ordered(Builder $query): void
    {
        $query->orderBy('sort');
    }
}
