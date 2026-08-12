<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SectionResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Section\SectionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SectionController extends Controller
{
    public function __construct(private readonly SectionService $service) {}

    public function index(): JsonResponse
    {
        try {
            return (new DataResponse(SectionResource::collection($this->service->all())))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }

    public function show(string $key): JsonResponse
    {
        try {
            return (new DataResponse(new SectionResource($this->service->byKey($key))))->toJson();
        } catch (ModelNotFoundException $e) {
            return (new ErrorResponse(__('messages.resource_not_found'), [], Response::HTTP_NOT_FOUND))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
