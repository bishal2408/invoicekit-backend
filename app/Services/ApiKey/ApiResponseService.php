<?php

namespace App\Services\ApiKey;

class ApiResponseService
{
    /**
     * __construct
     */
    public function __construct(
        public bool $success,
        public string $message,
        public int $responseCode,
        public mixed $detail = null,
    ) {}

    /**
     * success
     *
     * @param  mixed  $message
     * @param  mixed  $responseCode
     * @param  mixed  $detail
     * @return self
     */
    public static function success($message, $responseCode, $detail = null)
    {
        return new self(true, $message, $responseCode, $detail);
    }

    /**
     * fail
     *
     * @param  string  $message
     * @param  int  $responseCode
     * @param  mixed  $detail
     * @return self
     */
    public static function fail($message, $responseCode, $detail = null)
    {
        return new self(false, $message, $responseCode, $detail);
    }

    /**
     * toArray
     *
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'response_code' => $this->responseCode,
            'detail' => $this->detail,
        ];
    }

    /**
     * toJsonResponse
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toJsonResponse()
    {
        return response()->json($this->toArray(), $this->responseCode);
    }
}
