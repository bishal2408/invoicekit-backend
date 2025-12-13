<?php

namespace App\Services\ApiKey;

use App\Repositories\ApiKey\UsageLoggerRepository;

class UsageLoggerService
{
    /**
     * usageLoggerRepository
     *
     * @var UsageLoggerRepository
     */
    private $usageLoggerRepository;

    /**
     * __construct
     */
    public function __construct(
        UsageLoggerRepository $usageLoggerRepository
    ) {
        $this->usageLoggerRepository = $usageLoggerRepository;
    }

    /**
     * log
     *
     * @param  mixed  $request
     * @param  mixed  $response
     * @param  mixed  $apiKey
     * @param  mixed  $startTime
     * @return void
     */
    public function log($request, $response, $apiKey, $startTime)
    {
        // current unix timestamp with millisecond
        $endTime = microtime(true);

        // calculate response time
        $responseTimeMs = (int) (($endTime - $startTime) * 1000);

        // log api key usage
        $this->usageLoggerRepository->store([
            'api_key_id' => $apiKey->id,
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'status_code' => $response->getStatusCode(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'response_time_ms' => $responseTimeMs,
        ]);

        // update last used date for api key
        $apiKey->touchLastUsed();
    }
}
