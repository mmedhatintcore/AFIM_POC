<?php

namespace App\DTOs\V1\Fund;

use App\DTOs\Common\AbstractDTO;

final class IndexFundsDTO extends AbstractDTO
{
    protected ?bool $isFeatured = null;
    protected ?string $groupKey = null;
    protected ?string $search = null;

    protected function map(array $data): bool
    {
        $filters = $data['filters'] ?? [];
        $this->isFeatured = isset($filters['is_featured']) ? (bool) $filters['is_featured'] : null;
        $this->groupKey = $filters['group_key'] ?? null;
        $this->search = $data['search'] ?? null;

        return true;
    }

    public function getIsFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function getGroupKey(): ?string
    {
        return $this->groupKey;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }
}
