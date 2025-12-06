<?php

namespace App\Repositories\User;

use App\Models\User\User;
use App\Repositories\Repository;

class UserRepository extends Repository
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel()
    {
        return User::class;
    }
}
