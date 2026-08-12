<?php

namespace App\Repositories;

use App\DTOs\V1\Fund\IndexFundsDTO;
use App\Models\Fund;
use App\Repositories\Interfaces\FundRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FundRepository implements FundRepositoryInterface
{
    public function list(IndexFundsDTO $dto): Collection
    {
        return Fund::query()
            ->published()
            ->when($dto->getIsFeatured() !== null, fn (Builder $q) => $q->where('is_featured', $dto->getIsFeatured()))
            ->when($dto->getGroupKey(), fn (Builder $q, string $group) => $q->where('group_key', $group))
            ->when($dto->getSearch(), function (Builder $q, string $term) {
                $like = '%'.$term.'%';
                $q->where(fn (Builder $inner) => $inner
                    ->where('name->en', 'like', $like)
                    ->orWhere('name->ar', 'like', $like));
            })
            ->ordered()
            ->get();
    }

    public function findBySlugOrFail(string $slug): Fund
    {
        return Fund::query()->published()->where('slug', $slug)->firstOrFail();
    }

    public function byGroup(string $groupKey): Collection
    {
        return Fund::query()->published()->where('group_key', $groupKey)->ordered()->get();
    }
}
