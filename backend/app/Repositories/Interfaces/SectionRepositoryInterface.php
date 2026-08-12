<?php

namespace App\Repositories\Interfaces;

use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;

interface SectionRepositoryInterface
{
    public function allEnabled(): Collection;

    public function findByKeyOrFail(string $key): Section;
}
