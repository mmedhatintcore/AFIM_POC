<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Faq\FaqService;
use Illuminate\Http\JsonResponse;

final class FaqController extends Controller
{
    public function __construct(private readonly FaqService $service) {}

    public function index(): JsonResponse
    {
        try {
            return (new DataResponse(FaqResource::collection($this->service->all())))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
