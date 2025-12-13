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
}
