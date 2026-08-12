<?php

namespace App\Repositories;

use App\DTOs\V1\Contact\StoreContactMessageDTO;
use App\Models\ContactMessage;
use App\Repositories\Interfaces\ContactMessageRepositoryInterface;

class ContactMessageRepository implements ContactMessageRepositoryInterface
{
    public function create(StoreContactMessageDTO $dto, string $locale): ContactMessage
    {
        return ContactMessage::create([...$dto->toArray(), 'locale' => $locale]);
    }
}
