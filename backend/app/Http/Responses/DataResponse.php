<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final class DataResponse
{
    public function __construct(
        private readonly mixed $data,
        private readonly ?string $message = null,
        private readonly int $status = Response::HTTP_OK,
    ) {}

    public function toJson(): JsonResponse
    {
        // Paginated resource collections already carry data/links/meta —
        // return them flat instead of double-wrapping (see api-conventions.md).
        if ($this->data instanceof ResourceCollection && $this->data->resource instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            $response = $this->data->response()->setStatusCode($this->status);

            if ($this->message !== null) {
                $response->setData((object) array_merge((array) $response->getData(), ['message' => $this->message]));
            }

            return $response;
        }

        $payload = ['data' => $this->data];

        if ($this->message !== null) {
            $payload['message'] = $this->message;
        }

        return new JsonResponse($payload, $this->status);
    }
}
