<?php

namespace App\Http\Controllers;

use App\Data\TokenMetadata;
use App\Http\Requests\ValidateSecretRequest;
use App\Services\ApiTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenController extends Controller
{
    public function __construct(private ApiTokenService $apiTokenService)
    {
    }


    public function validate(ValidateSecretRequest $request): JsonResponse
    {
        $valid = $this->apiTokenService->validateSecret($request->input('secret'));

        if(! $valid) {
            return response()->json([
                'message' => 'Unauthorised'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $result = $this->apiTokenService->issueToken(new TokenMetadata(deviceName: $request->userAgent(), ip: $request->ip()));

        return response()->json([
           'token' => $result['plainTextToken'],
           'expires_at' => $result['apiToken']->getExpiresAt(),
        ], Response::HTTP_CREATED);
    }
}
