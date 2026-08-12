<?php

namespace App\DTOs\V1\Contact;

use App\DTOs\Common\AbstractDTO;

final class StoreContactMessageDTO extends AbstractDTO
{
    protected string $name;
    protected string $email;
    protected ?string $phone = null;
    protected ?string $subject = null;
    protected string $message;

    protected function map(array $data): bool
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'] ?? null;
        $this->subject = $data['subject'] ?? null;
        $this->message = $data['message'];

        return true;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }
}
