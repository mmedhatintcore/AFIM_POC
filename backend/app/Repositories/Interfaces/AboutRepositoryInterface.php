<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface AboutRepositoryInterface
{
    public function timeline(): Collection;

    public function teamMembers(?string $group = null): Collection;

    public function committees(): Collection;
}
