<?php

namespace App\Http\Controllers;

use App\Http\Resources\TimerResource;
use App\Services\ApiTokenService;
use App\Services\TimerService;
use App\Traits\LogsTimerActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTimerController extends Controller
{
    use LogsTimerActivity;
    public function __construct(private TimerService $timers, private ApiTokenService $apiTokenService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $this->apiTokenService->updateLastUsedAt($request->attributes->get('apiToken'));

        $timers = $this->timers->all();
        return response()->json(TimerResource::collection($timers));
    }

    public function forUuid(Request $request, string $uuid): JsonResponse
    {
        $this->apiTokenService->updateLastUsedAt($request->attributes->get('apiToken'));

        $timer = $this->timers->findByUuid($uuid);

        if(is_null($timer)) {
            return response()->json([
                'message' => 'Not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(new TimerResource($timer));
    }
}
