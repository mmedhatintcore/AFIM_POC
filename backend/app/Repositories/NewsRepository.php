<?php

namespace App\Repositories;

use App\DTOs\V1\News\IndexNewsDTO;
use App\Models\NewsPost;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository implements NewsRepositoryInterface
{
    public function paginate(IndexNewsDTO $dto): LengthAwarePaginator
    {
        $direction = str_starts_with($dto->getSort(), '-') ? 'desc' : 'asc';

        return NewsPost::query()
            ->published()
            ->when($dto->getType(), fn (Builder $q, string $type) => $q->ofType($type))
            ->when($dto->getSearch(), fn (Builder $q, string $term) => $q->search($term))
            ->orderBy('published_at', $direction)
            ->paginate($dto->getPerPage())
            ->withQueryString();
    }

    public function findBySlugOrFail(string $slug): NewsPost
    {
        return NewsPost::query()->published()->where('slug', $slug)->firstOrFail();
    }

    public function related(NewsPost $post, int $limit = 3): Collection
    {
        return NewsPost::query()
            ->published()
            ->ofType($post->type)
            ->whereKeyNot($post->getKey())
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
