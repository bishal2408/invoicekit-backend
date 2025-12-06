<?php

namespace App\Services\ApiKey;

use App\Models\ApiKey\ApiKeyEnvironment;
use App\Repositories\Repository;

class EnvironmentRepository extends Repository
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel()
    {
        return ApiKeyEnvironment::class;
    }
}
