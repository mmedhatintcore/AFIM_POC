<?php

namespace App\Repositories;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function allPublished(): Collection
    {
        return Service::query()->published()->ordered()->get();
    }

    public function findBySlugOrFail(string $slug): Service
    {
        return Service::query()->published()->where('slug', $slug)->firstOrFail();
    }
}
