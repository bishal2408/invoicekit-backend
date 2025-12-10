<?php

namespace App\Repositories\ApiKey;

use App\Models\ApiKey\ApiKey;
use App\Repositories\Repository;

class ApiKeyRepository extends Repository
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel()
    {
        return ApiKey::class;
    }
}
