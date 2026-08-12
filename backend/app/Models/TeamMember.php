<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model
{
    use HasTranslations;

    public const GROUPS = ['board', 'leadership'];

    public array $translatable = ['name', 'role'];

    protected $fillable = ['group', 'name', 'role', 'sort', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'sort' => 'integer'];
    }

    #[Scope]
    public function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    public function inGroup(Builder $query, string $group): void
    {
        $query->where('group', $group);
    }

    #[Scope]
    public function ordered(Builder $query): void
    {
        $query->orderBy('sort');
    }
}
