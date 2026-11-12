<?php

namespace App\Http\Controllers;

use App\Data\TokenMetadata;
use App\Http\Requests\ValidateSecretRequest;
use App\Services\ApiTokenService;
use App\Traits\LogsApiActivity;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenController extends Controller
{
    use LogsApiActivity;

    public function __construct(private ApiTokenService $apiTokenService, private LoggerInterface $logger)
    {
    }


    public function validate(ValidateSecretRequest $request): JsonResponse
    {
        $this->logApiActivity('VALIDATE_ATTEMPT', 'Secret validation attempted', ['user_agent' => $request->userAgent()]);

        $valid = $this->apiTokenService->validateSecret($request->input('secret'));

        if(! $valid) {
            $this->logApiActivity('VALIDATE_FAILED', 'Invalid secret', ['user_agent' => $request->userAgent()], 'warning');

            return response()->json([
                'message' => 'Unauthorised'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $result = $this->apiTokenService->issueToken(new TokenMetadata(deviceName: $request->userAgent(), ip: $request->ip()));

        $this->logApiActivity('TOKEN_ISSUED', 'Token issued with id ' . $result['apiToken']->getId(), [
            'token_id' => $result['apiToken']->getId(),
            'user_agent' => $request->userAgent(),
            'expires_at' => $result['apiToken']->getExpiresAt(),
        ]);

        return response()->json([
           'token' => $result['plainTextToken'],
           'expires_at' => $result['apiToken']->getExpiresAt(),
        ], Response::HTTP_CREATED);
    }
}
