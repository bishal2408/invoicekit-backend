<?php

namespace App\Services\ApiKey\Authenticate;

use App\Models\ApiKey\ApiKey;

class AuthResultService
{
    /**
     * __construct
     */
    public function __construct(
        public bool $success,
        public string $message,
        public int $code,
        public ?ApiKey $key = null,
    ) {}

    /**
     * success
     *
     * @param  mixed  $message
     * @param  mixed  $code
     * @param  ApiKey|null  $key
     * @return self
     */
    public static function success($message, $code, $key)
    {
        return new self(true, $message, $code, $key);
    }

    /**
     * fail
     *
     * @param  string  $message
     * @param  int  $code
     * @return self
     */
    public static function fail($message, $code)
    {
        return new self(false, $message, $code);
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
            'code' => $this->code,
        ];
    }
}
