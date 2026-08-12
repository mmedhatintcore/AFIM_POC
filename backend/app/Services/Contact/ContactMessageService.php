<?php

namespace App\Services\Contact;

use App\DTOs\V1\Contact\StoreContactMessageDTO;
use App\Models\ContactMessage;
use App\Repositories\Interfaces\ContactMessageRepositoryInterface;

final class ContactMessageService
{
    public function __construct(
        private readonly ContactMessageRepositoryInterface $messages,
    ) {}

    public function store(StoreContactMessageDTO $dto): ContactMessage
    {
        return $this->messages->create($dto, app()->getLocale());
    }
}
