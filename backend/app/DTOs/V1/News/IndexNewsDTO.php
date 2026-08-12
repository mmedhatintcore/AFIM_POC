<?php

namespace App\DTOs\V1\News;

use App\DTOs\Common\AbstractDTO;

final class IndexNewsDTO extends AbstractDTO
{
    protected ?string $type = null;
    protected ?string $search = null;
    protected string $sort = '-published_at';
    protected int $perPage = 9;

    protected function map(array $data): bool
    {
        $this->type = $data['filters']['type'] ?? null;
        $this->search = $data['search'] ?? null;
        $this->sort = $data['sort'] ?? '-published_at';
        $this->perPage = min((int) ($data['per_page'] ?? 9), 50);

        return true;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
