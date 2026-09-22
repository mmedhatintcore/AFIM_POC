<?php

namespace App\Repositories;

use App\Models\FinderQuestion;
use App\Repositories\Interfaces\FinderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FinderRepository implements FinderRepositoryInterface
{
    public function questions(): Collection
    {
        return FinderQuestion::query()->ordered()->get();
    }
}
