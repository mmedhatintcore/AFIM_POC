<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface FinderRepositoryInterface
{
    public function questions(): Collection;
}
