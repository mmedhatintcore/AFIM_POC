<?php

namespace App\Services\Service;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class ServiceCatalogService
{
    public function __construct(
        private readonly ServiceRepositoryInterface $services,
    ) {}

    public function all(): Collection
    {
        return $this->services->allPublished();
    }

    public function bySlug(string $slug): Service
    {
        return $this->services->findBySlugOrFail($slug);
    }
}
