<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\Fund\IndexFundsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\IndexFundsRequest;
use App\Http\Resources\FundResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Fund\FundService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FundController extends Controller
{
    public function __construct(private readonly FundService $service) {}

    public function index(IndexFundsRequest $request): JsonResponse
    {
        try {
            $dto = new IndexFundsDTO($request->validated());

            return (new DataResponse(FundResource::collection($this->service->list($dto))))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            return (new DataResponse(new FundResource($this->service->bySlug($slug))))->toJson();
        } catch (ModelNotFoundException $e) {
            return (new ErrorResponse(__('messages.resource_not_found'), [], Response::HTTP_NOT_FOUND))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
