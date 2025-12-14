<?php

namespace App\Repositories\ApiKey;

use App\Models\ApiKey\ApiKeyUsage;
use App\Repositories\Repository;

class UsageLoggerRepository extends Repository
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel()
    {
        return ApiKeyUsage::class;
    }

    /**
     * countByApiKeyId
     *
     * @param  mixed  $apiKeyId
     * @return mixed
     */
    public function countByApiKeyId($apiKeyId)
    {
        return $this->model
            ->where('api_key_id', $apiKeyId)
            ->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->where('status_code', '<', 400) // count only successful responses
            ->count();
    }
}
