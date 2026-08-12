<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ErrorResponse
{
    public function __construct(
        private readonly string $message,
        private readonly array $errors = [],
        private readonly int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
    ) {}

    public function toJson(): JsonResponse
    {
        $payload = ['message' => $this->message];

        if ($this->errors !== []) {
            $payload['errors'] = $this->errors;
        }

        return new JsonResponse($payload, $this->status);
    }
}
