<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\News\IndexNewsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\IndexNewsRequest;
use App\Http\Resources\NewsPostDetailResource;
use App\Http\Resources\NewsPostResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\News\NewsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class NewsController extends Controller
{
    public function __construct(private readonly NewsService $service) {}

    public function index(IndexNewsRequest $request): JsonResponse
    {
        try {
            $dto = new IndexNewsDTO($request->validated());

            return (new DataResponse(NewsPostResource::collection($this->service->paginate($dto))))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $post = $this->service->bySlug($slug);
            $related = $this->service->related($post);

            return (new DataResponse(new NewsPostDetailResource($post, $related)))->toJson();
        } catch (ModelNotFoundException $e) {
            return (new ErrorResponse(__('messages.resource_not_found'), [], Response::HTTP_NOT_FOUND))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
