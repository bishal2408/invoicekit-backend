<?php

namespace App\Services\ApiKey\Authenticate;

use App\Repositories\ApiKey\ApiKeyRepository;
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
     * __construct
     */
    public function __construct(ApiKeyRepository $apiKeyRepository)
    {
        $this->apiKeyRepository = $apiKeyRepository;
    }

    /**
     * authenticate
     *
     * @return AuthResultService
     */
    public function authenticate(Request $request)
    {
        // extract api key from request header
        $rawkey = $this->extractApiKey($request);

        // if api key is missing
        if (! $rawkey) {
            return AuthResultService::fail('API_KEY_MISSING', 401);
        }

        // extract prefix from api key
        $prefix = $this->extractPrefix($rawkey);

        // get api key from database
        $key = $this->apiKeyRepository->findActiveKeyByPrefix($prefix);

        // if api key doesnot exist; or if key hash doesnot match
        if (! $key || ! $this->isValidHash($key->key_hash, $rawkey)) {
            return AuthResultService::fail('API_KEY_INVALID', 401);
        }

        // if api key is already expired
        if ($key->isExpired()) {
            return AuthResultService::fail('API_KEY_EXPIRED', 403);
        }

        // attach api key model to the request
        $request->attributes->set('api_key', $key);

        // return success response
        return AuthResultService::success('API_KEY_VALID', 200, $key);
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

    /**
     * extractPrefix
     *
     * @param  string  $apiKey
     * @return mixed
     */
    private function extractPrefix($apiKey)
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
    private function extractApiKey(Request $request)
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
}
