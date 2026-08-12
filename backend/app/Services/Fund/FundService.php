<?php

namespace App\Services\Fund;

use App\DTOs\V1\Fund\IndexFundsDTO;
use App\Models\Fund;
use App\Repositories\Interfaces\FundCategoryRepositoryInterface;
use App\Repositories\Interfaces\FundRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class FundService
{
    public function __construct(
        private readonly FundRepositoryInterface $funds,
        private readonly FundCategoryRepositoryInterface $categories,
    ) {}

    public function list(IndexFundsDTO $dto): Collection
    {
        return $this->funds->list($dto);
    }

    public function bySlug(string $slug): Fund
    {
        return $this->funds->findBySlugOrFail($slug);
    }

    public function categories(): Collection
    {
        return $this->categories->allOrdered();
    }
}
