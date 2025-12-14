<?php

namespace App\Constants;

class ApiErrorCode
{
    // api key is missing
    public const API_KEY_MISSING = 'API_KEY_MISSING';

    // api key is invalid
    public const API_KEY_INVALID = 'API_KEY_INVALID';

    // api key is expired
    public const API_KEY_EXPIRED = 'API_KEY_EXPIRED';

    // rate limit exceeded
    public const RATE_LIMIT_EXCEEDED = 'RATE_LIMIT_EXCEEDED';

    // monthly request limit exceeded
    public const MONTHLY_REQUEST_LIMIT_EXCEEDED = 'MONTHLY_REQUEST_LIMIT_EXCEEDED';
}
