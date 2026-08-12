<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\IndexTeamMembersRequest;
use App\Http\Resources\TeamMemberResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\About\AboutService;
use Illuminate\Http\JsonResponse;

final class TeamMemberController extends Controller
{
    public function __construct(private readonly AboutService $service) {}

    public function index(IndexTeamMembersRequest $request): JsonResponse
    {
        try {
            $group = $request->validated()['filters']['group'] ?? null;

            return (new DataResponse(TeamMemberResource::collection($this->service->teamMembers($group))))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);

            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
