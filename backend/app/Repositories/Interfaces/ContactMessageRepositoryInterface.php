<?php

namespace App\Repositories\Interfaces;

use App\DTOs\V1\Contact\StoreContactMessageDTO;
use App\Models\ContactMessage;

interface ContactMessageRepositoryInterface
{
    public function create(StoreContactMessageDTO $dto, string $locale): ContactMessage;
}
