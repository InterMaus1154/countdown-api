<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Throttle responses are produced before the controller runs, so log them here.
        // Returning null lets Laravel render the normal 429 response.
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            logger()->warning(
                sprintf(
                    '[API Activity] %s %s /%s: Activity type: RATE_LIMITED, Too many requests, retry after %s seconds',
                    $request->ip() ?? '',
                    $request->method(),
                    $request->path(),
                    $e->getHeaders()['Retry-After'] ?? 'unknown'
                ),
                ['user_agent' => $request->userAgent()]
            );

            return null;
        });
    })->create();
