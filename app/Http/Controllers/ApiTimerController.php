<?php

namespace App\Http\Controllers;

use App\Http\Resources\TimerResource;
use App\Services\ApiTokenService;
use App\Services\AuthService;
use App\Services\TimerService;
use App\Traits\LogsTimerActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class ApiTimerController extends Controller
{
    use LogsTimerActivity;
    public function __construct(
        private TimerService $timers,
        private ApiTokenService $apiTokenService,
        private AuthService $auth,
        private LoggerInterface $logger
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $apiToken = $request->attributes->get('apiToken');
        $this->apiTokenService->updateLastUsedAt($apiToken);

        $timers = $this->timers->all();

        $this->logTimerActivity('API_TIMERS_LISTED', sprintf('Token %s listed %d timers', $apiToken->getId(), count($timers)), ['token_id' => $apiToken->getId()]);

        return response()->json(TimerResource::collection($timers));
    }

    public function forUuid(Request $request, string $uuid): JsonResponse
    {
        $apiToken = $request->attributes->get('apiToken');
        $this->apiTokenService->updateLastUsedAt($apiToken);

        $timer = $this->timers->findByUuid($uuid);

        if(is_null($timer)) {
            $this->logTimerActivity('API_TIMER_NOT_FOUND', sprintf('Token %s requested unknown timer %s', $apiToken->getId(), $uuid), ['token_id' => $apiToken->getId()]);

            return response()->json([
                'message' => 'Not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->logTimerActivity('API_TIMER_FETCHED', sprintf('Token %s fetched timer %s', $apiToken->getId(), $uuid), ['token_id' => $apiToken->getId()]);

        return response()->json(new TimerResource($timer));
    }
}
