<?php

namespace App\Services\About;

use App\Repositories\Interfaces\AboutRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class AboutService
{
    public function __construct(
        private readonly AboutRepositoryInterface $about,
    ) {}

    public function timeline(): Collection
    {
        return $this->about->timeline();
    }

    public function teamMembers(?string $group = null): Collection
    {
        return $this->about->teamMembers($group);
    }

    public function committees(): Collection
    {
        return $this->about->committees();
    }
}
