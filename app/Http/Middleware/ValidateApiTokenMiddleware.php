<?php

namespace App\Http\Middleware;

use App\Services\ApiTokenService;
use App\Traits\LogsApiActivity;
use Closure;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiTokenMiddleware
{
    use LogsApiActivity;

    public function __construct(private ApiTokenService $apiTokenService, private LoggerInterface $logger)
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
            $this->logApiActivity(
                'TOKEN_REJECTED',
                is_null($request->bearerToken()) ? 'No bearer token provided' : 'Invalid or expired token',
                ['user_agent' => $request->userAgent()],
                'warning'
            );

            return response()->json([
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $request->attributes->set('apiToken', $token);

        return $next($request);
    }
}
