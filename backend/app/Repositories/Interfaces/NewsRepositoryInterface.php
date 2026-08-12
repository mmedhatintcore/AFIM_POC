<?php

namespace App\Repositories\Interfaces;

use App\DTOs\V1\News\IndexNewsDTO;
use App\Models\NewsPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface NewsRepositoryInterface
{
    public function paginate(IndexNewsDTO $dto): LengthAwarePaginator;

    public function findBySlugOrFail(string $slug): NewsPost;

    public function related(NewsPost $post, int $limit = 3): Collection;
}
