<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FinderQuestionResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Finder\FinderService;
use Illuminate\Http\JsonResponse;

final class FinderController extends Controller
{
    public function __construct(private readonly FinderService $service) {}

    public function questions(): JsonResponse
    {
        try {
            return (new DataResponse(FinderQuestionResource::collection($this->service->questions())))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
