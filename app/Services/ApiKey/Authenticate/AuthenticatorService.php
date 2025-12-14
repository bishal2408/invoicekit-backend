<?php

namespace App\Services\ApiKey\Authenticate;

use App\Constants\ApiErrorCode;
use App\Repositories\ApiKey\ApiKeyRepository;
use App\Repositories\ApiKey\UsageLoggerRepository;
use App\Services\ApiKey\ApiResponseService;
use Illuminate\Http\Request;

class AuthenticatorService
{
    /**
     * apiKeyRepository
     *
     * @var ApiKeyRepository
     */
    private $apiKeyRepository;

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
        ApiKeyRepository $apiKeyRepository,
        UsageLoggerRepository $usageLoggerRepository
    ) {
        $this->apiKeyRepository = $apiKeyRepository;
        $this->usageLoggerRepository = $usageLoggerRepository;
    }

    /**
     * authenticate
     *
     * @return ApiResponseService
     */
    public function authenticate(Request $request)
    {
        // extract api key from request header
        $rawkey = $this->extractApiKey($request);

        // if api key is missing
        if (! $rawkey) {
            return ApiResponseService::fail(ApiErrorCode::API_KEY_MISSING, 401);
        }

        // extract prefix from api key
        $prefix = $this->extractPrefix($rawkey);

        // get api key from database
        $key = $this->findActiveKeyByPrefix($prefix);

        // if api key doesnot exist; or if key hash doesnot match
        if (! $key || ! $this->isValidHash($key->key_hash, $rawkey)) {
            return ApiResponseService::fail(ApiErrorCode::API_KEY_INVALID, 401);
        }

        // if api key is already expired
        if ($key->isExpired()) {
            return ApiResponseService::fail(ApiErrorCode::API_KEY_EXPIRED, 403);
        }

        if ($this->isMonthlyRateLimitExceeded($key)) {
            return ApiResponseService::fail(ApiErrorCode::MONTHLY_REQUEST_LIMIT_EXCEEDED, 429);
        }

        // attach api key model to the request
        $request->attributes->set('api_key', $key);

        // return success response
        return ApiResponseService::success('API_KEY_VALID', 200, $key);
    }

    /**
     * findActiveKeyByPrefix
     *
     * @param  string  $prefix
     * @return mixed
     */
    public function findActiveKeyByPrefix($prefix)
    {
        return $this->apiKeyRepository->findActiveKeyByPrefix($prefix);
    }

    /**
     * extractPrefix
     *
     * @param  string  $apiKey
     * @return mixed
     */
    public function extractPrefix($apiKey)
    {
        // extract prefix from api key
        $parts = explode('_', $apiKey, 4);

        // lookup prefix part of api
        return $parts[2] ?? null;
    }

    /**
     * Extract the api key from the request.
     *
     * @return string|null
     */
    public function extractApiKey(Request $request)
    {
        // extract api key from header
        $header = $request->header('Authorization');

        // check if header is set and valid
        if (! $header || ! str_starts_with($header, 'Bearer ')) {
            return null;
        }

        // return api key from response
        return trim(str_replace('Bearer ', '', $header));
    }

    /**
     * isMonthlyRateLimitExceeded
     *
     * @param  mixed  $apiKey
     * @return mixed
     */
    private function isMonthlyRateLimitExceeded($apiKey)
    {
        // monthly limit
        $monthlyLimit = $apiKey->getEffectiveMonthlyRequestLimit();

        // if monthly limit is not set; meaning unlimited
        if (! $monthlyLimit) {
            return;
        }

        // usuage count
        $usageCount = $this->usageLoggerRepository->countByApiKeyId($apiKey->id);

        // if usage count is greater than monthly limit
        if ($usageCount >= $monthlyLimit) {
            return true;
        }
    }

    /**
     * isValidHash
     *
     * @param  string  $validKeyHash
     * @param  string  $requestKey
     * @return bool
     */
    private function isValidHash($validKeyHash, $requestKey)
    {
        return hash_equals(
            $validKeyHash,
            hash(config('auth.passwords.algorithm'), $requestKey)
        );
    }
}
