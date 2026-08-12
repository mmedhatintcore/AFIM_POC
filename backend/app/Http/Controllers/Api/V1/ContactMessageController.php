<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\Contact\StoreContactMessageDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreContactMessageRequest;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Contact\ContactMessageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ContactMessageController extends Controller
{
    public function __construct(private readonly ContactMessageService $service) {}

    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        try {
            $dto = new StoreContactMessageDTO($request->validated());
            $message = $this->service->store($dto);

            return (new DataResponse(['id' => $message->id], __('messages.contact_received'), Response::HTTP_CREATED))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
