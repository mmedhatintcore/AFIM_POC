<?php

namespace App\Services\Finder;

use App\Repositories\Interfaces\FinderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class FinderService
{
    public function __construct(
        private readonly FinderRepositoryInterface $finder,
    ) {}

    public function questions(): Collection
    {
        return $this->finder->questions();
    }
}
