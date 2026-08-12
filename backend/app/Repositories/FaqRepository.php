<?php

namespace App\Repositories;

use App\Models\Faq;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FaqRepository implements FaqRepositoryInterface
{
    public function allPublished(): Collection
    {
        return Faq::query()->published()->ordered()->get();
    }
}
