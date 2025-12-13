<?php

namespace App\Http\Middleware;

use App\Models\ApiKey\ApiKey;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // api key
        $apiKey = $this->extractApiKey($request);

        if (! $apiKey) {
            $message = $this->invalidResponse('API_KEY_MISSING', 401);

            // missing response
            return response()->json($message, $message['code']);
        }

        // validate api key
        $response = $this->validateApiKey($apiKey);

        // if validation fails
        if ($response && ! $response['success']) {
            return response()->json($response, $response['code']);
        }

        // attch key to the request
        $request->attributes->set('api_key', $apiKey);

        // if validation succeeds
        return $next($request);
    }

    /**
     * validate api key.
     *
     * @param  string  $apiKey
     * @return mixed
     */
    private function validateApiKey($apiKey)
    {
        // extract prefix from api key
        $parts = explode('_', $apiKey, 4);

        // lookup prefix part of api
        $prefix = $parts[2] ?? null;

        // key exists
        $key = ApiKey::where('key_prefix', $prefix)
            ->where('is_active', true)
            ->first();

        // if key doesnot exist
        if (! $key) {
            $message = $this->invalidResponse('API_KEY_INVALID', 401);

            // comppare key hash
        } elseif (! $this->isValidHash($key->key_hash, $apiKey)) {
            $message = $this->invalidResponse('API_KEY_INVALID', 401);

            // is key expired
        } elseif ($this->isKeyExpired($key->expires_at)) {
            $message = $this->invalidResponse('API_KEY_EXPIRED', 403);
        } else {
            // kay is valid
            $message = [
                'success' => true,
                'message' => 'API_KEY_VALID',
                'code' => 200,
            ];
        }

        return $message;
    }

    /**
     * isKeyExpired
     *
     * @param  mixed  $expiresAt
     * @return bool
     */
    private function isKeyExpired($expiresAt)
    {
        // parse string to date
        $expiresAt = $expiresAt ? Carbon::parse($expiresAt) : null;

        // check if key is expired
        return $expiresAt && now()->greaterThan($expiresAt);
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

    /**
     * invalidResponse
     *
     * @param  string  $message
     * @param  int  $code
     * @return mixed
     */
    private function invalidResponse($message, $code)
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => $code,
        ];
    }
}
