<?php

namespace App\Repositories;

use App\Models\FundCategory;
use App\Repositories\Interfaces\FundCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FundCategoryRepository implements FundCategoryRepositoryInterface
{
    public function allOrdered(): Collection
    {
        return FundCategory::query()->ordered()->get();
    }

    public function allKeyed(): Collection
    {
        return $this->allOrdered()->keyBy('key');
    }
}
