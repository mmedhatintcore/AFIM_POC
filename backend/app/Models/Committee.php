<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Committee extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'mission'];

    protected $fillable = ['name', 'mission', 'responsibilities', 'members', 'sort'];

    protected function casts(): array
    {
        return ['responsibilities' => 'array', 'members' => 'array', 'sort' => 'integer'];
    }

    #[Scope]
    public function ordered(Builder $query): void
    {
        $query->orderBy('sort');
    }
}
