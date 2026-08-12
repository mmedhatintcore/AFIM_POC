<?php

namespace App\Repositories;

use App\Models\Section;
use App\Repositories\Interfaces\SectionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SectionRepository implements SectionRepositoryInterface
{
    public function allEnabled(): Collection
    {
        return Section::query()->enabled()->get();
    }

    public function findByKeyOrFail(string $key): Section
    {
        return Section::query()->enabled()->where('key', $key)->firstOrFail();
    }
}
