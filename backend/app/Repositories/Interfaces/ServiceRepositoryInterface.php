<?php

namespace App\Repositories\Interfaces;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

interface ServiceRepositoryInterface
{
    public function allPublished(): Collection;

    public function findBySlugOrFail(string $slug): Service;
}
