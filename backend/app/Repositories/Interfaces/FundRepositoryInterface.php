<?php

namespace App\Repositories\Interfaces;

use App\DTOs\V1\Fund\IndexFundsDTO;
use App\Models\Fund;
use Illuminate\Database\Eloquent\Collection;

interface FundRepositoryInterface
{
    public function list(IndexFundsDTO $dto): Collection;

    public function findBySlugOrFail(string $slug): Fund;

    public function byGroup(string $groupKey): Collection;
}
