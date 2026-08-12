<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Service\ServiceCatalogService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ServiceController extends Controller
{
    public function __construct(private readonly ServiceCatalogService $service) {}

    public function index(): JsonResponse
    {
        try {
            return (new DataResponse(ServiceResource::collection($this->service->all())))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            return (new DataResponse(new ServiceResource($this->service->bySlug($slug))))->toJson();
        } catch (ModelNotFoundException $e) {
            return (new ErrorResponse(__('messages.resource_not_found'), [], Response::HTTP_NOT_FOUND))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
