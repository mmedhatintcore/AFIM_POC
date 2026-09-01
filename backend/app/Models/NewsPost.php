<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NewsPost extends Model
{
    use HasTranslations;

    public const TYPES = ['press', 'media', 'social'];

    public array $translatable = ['title', 'excerpt', 'body'];

    protected $fillable = ['slug', 'type', 'source', 'title', 'excerpt', 'body', 'image_path', 'published_at', 'is_published'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_published' => 'boolean'];
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }

    #[Scope]
    public function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    public function ofType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    #[Scope]
    public function search(Builder $query, string $term): void
    {
        $like = '%'.$term.'%';
        $query->where(function (Builder $q) use ($like) {
            $q->where('title->en', 'like', $like)
                ->orWhere('title->ar', 'like', $like)
                ->orWhere('excerpt->en', 'like', $like)
                ->orWhere('excerpt->ar', 'like', $like)
                ->orWhere('source', 'like', $like);
        });
    }
}
