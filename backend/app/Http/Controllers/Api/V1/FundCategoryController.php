<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FundCategoryResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Fund\FundService;
use Illuminate\Http\JsonResponse;

final class FundCategoryController extends Controller
{
    public function __construct(private readonly FundService $service) {}

    public function index(): JsonResponse
    {
        try {
            return (new DataResponse(FundCategoryResource::collection($this->service->categories())))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
