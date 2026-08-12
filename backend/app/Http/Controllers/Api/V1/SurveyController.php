<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\Survey\SubmitSurveyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SubmitSurveyRequest;
use App\Http\Resources\FundCategoryResource;
use App\Http\Resources\FundResource;
use App\Http\Resources\SurveyQuestionResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Survey\SurveyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final class SurveyController extends Controller
{
    public function __construct(private readonly SurveyService $service) {}

    public function questions(): JsonResponse
    {
        try {
            return (new DataResponse(SurveyQuestionResource::collection($this->service->questions())))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }

    public function submit(SubmitSurveyRequest $request): JsonResponse
    {
        try {
            $dto = new SubmitSurveyDTO($request->validated());
            $result = $this->service->submit($dto);

            return (new DataResponse([
                'category' => (new FundCategoryResource($result['category']))->toArray($request),
                'alternative' => $result['alternative'] !== null
                    ? (new FundCategoryResource($result['alternative']))->toArray($request)
                    : null,
                'is_islamic' => $result['is_islamic'],
                'is_corporate' => $result['is_corporate'],
                'profile' => $result['profile'],
                'funds' => FundResource::collection($result['funds'])->toArray($request),
            ], __('messages.survey_result_ready'), Response::HTTP_CREATED))->toJson();
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
