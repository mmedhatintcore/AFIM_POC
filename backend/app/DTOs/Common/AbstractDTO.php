<?php

namespace App\DTOs\Common;

abstract class AbstractDTO
{
    public function __construct(array $data)
    {
        $this->map($data);
    }

    abstract protected function map(array $data): bool;
}
