<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Models\TeamMember;
use App\Models\TimelineMilestone;
use App\Repositories\Interfaces\AboutRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AboutRepository implements AboutRepositoryInterface
{
    public function timeline(): Collection
    {
        return TimelineMilestone::query()->ordered()->get();
    }

    public function teamMembers(?string $group = null): Collection
    {
        return TeamMember::query()
            ->published()
            ->when($group, fn (Builder $q, string $g) => $q->inGroup($g))
            ->orderBy('group')
            ->ordered()
            ->get();
    }

    public function committees(): Collection
    {
        return Committee::query()->ordered()->get();
    }
}
