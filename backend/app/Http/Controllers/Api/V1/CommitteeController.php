<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommitteeResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\About\AboutService;
use Illuminate\Http\JsonResponse;

final class CommitteeController extends Controller
{
    public function __construct(private readonly AboutService $service) {}

    public function index(): JsonResponse
    {
        try {
            return (new DataResponse(CommitteeResource::collection($this->service->committees())))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
