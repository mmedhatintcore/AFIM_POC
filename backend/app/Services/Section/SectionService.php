<?php

namespace App\Services\Section;

use App\Models\Section;
use App\Repositories\Interfaces\SectionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class SectionService
{
    public function __construct(
        private readonly SectionRepositoryInterface $sections,
    ) {}

    public function all(): Collection
    {
        return $this->sections->allEnabled();
    }

    public function byKey(string $key): Section
    {
        return $this->sections->findByKeyOrFail($key);
    }
}
