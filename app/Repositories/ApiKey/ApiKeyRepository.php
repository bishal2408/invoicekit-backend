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

    /**
     * findActiveKeyByPrefix
     *
     * @param  string  $prefix
     * @return mixed
     */
    public function findActiveKeyByPrefix($prefix)
    {
        return $this->model
            ->where('key_prefix', $prefix)
            ->where('is_active', true)
            ->first();
    }
}
