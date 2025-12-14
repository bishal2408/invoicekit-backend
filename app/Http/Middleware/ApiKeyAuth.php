<?php

namespace App\Http\Middleware;

use App\Services\ApiKey\Authenticate\AuthenticatorService;
use App\Services\ApiKey\UsageLoggerService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * authenticatorService
     *
     * @var AuthenticatorService
     */
    private $authenticatorService;

    /**
     * usageLoggerService
     *
     * @var UsageLoggerService
     */
    private $usageLoggerService;

    /**
     * __construct
     */
    public function __construct(
        AuthenticatorService $authenticatorService,
        UsageLoggerService $usageLoggerService
    ) {
        $this->authenticatorService = $authenticatorService;
        $this->usageLoggerService = $usageLoggerService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // current unix timestamp with millisecond
        $startTime = microtime(true);

        // authenticate api key
        $result = $this->authenticatorService->authenticate($request);

        // if validation fails
        if (! $result->success) {
            return response()->json(
                $result->toArray(),
                $result->responseCode
            );
        }

        /** @var Response $response */
        $response = $next($request);

        // log usuage after response
        $this->usageLoggerService->log(
            $request,
            $response,
            $result->detail, // here detail is api key
            $startTime
        );

        // return response
        return $response;
    }
}
