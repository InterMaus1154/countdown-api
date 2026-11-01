<?php

namespace App\Http\Middleware;

use App\Services\ApiTokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiTokenMiddleware
{
    public function __construct(private ApiTokenService $apiTokenService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->apiTokenService->validateToken($request->bearerToken());
        if(is_null($token)){
            return response()->json([
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $request->attributes->set('apiToken', $token);

        return $next($request);
    }
}
