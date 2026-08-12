<?php

namespace App\Services\News;

use App\DTOs\V1\News\IndexNewsDTO;
use App\Models\NewsPost;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class NewsService
{
    public function __construct(
        private readonly NewsRepositoryInterface $news,
    ) {}

    public function paginate(IndexNewsDTO $dto): LengthAwarePaginator
    {
        return $this->news->paginate($dto);
    }

    public function bySlug(string $slug): NewsPost
    {
        return $this->news->findBySlugOrFail($slug);
    }

    public function related(NewsPost $post): Collection
    {
        return $this->news->related($post);
    }
}
