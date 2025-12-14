<?php

use App\Constants\ApiErrorCode;
use App\Http\Middleware\ApiKeyAuth;
use App\Services\ApiKey\ApiResponseService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api.key' => ApiKeyAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (ThrottleRequestsException $e) {
            return ApiResponseService::fail(
                ApiErrorCode::TOO_MANY_ATTEMPTS,
                429,
                $e->getMessage()
            )->toJsonResponse();
        });
    })->create();
