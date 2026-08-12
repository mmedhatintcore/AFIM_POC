<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TimelineMilestone extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'body'];

    protected $fillable = ['year', 'title', 'body', 'sort'];

    protected function casts(): array
    {
        return ['sort' => 'integer'];
    }

    #[Scope]
    public function ordered(Builder $query): void
    {
        $query->orderBy('sort');
    }
}
