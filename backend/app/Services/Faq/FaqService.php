<?php

namespace App\Services\Faq;

use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class FaqService
{
    public function __construct(
        private readonly FaqRepositoryInterface $faqs,
    ) {}

    public function all(): Collection
    {
        return $this->faqs->allPublished();
    }
}
