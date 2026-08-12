<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface FundCategoryRepositoryInterface
{
    public function allOrdered(): Collection;

    /** Keyed by category `key` for scoring lookups. */
    public function allKeyed(): Collection;
}
