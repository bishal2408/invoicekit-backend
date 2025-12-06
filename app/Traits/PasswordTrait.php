<?php

namespace App\Traits;

trait PasswordTrait
{
    /**
     * generatePasswordSalt
     *
     * @param  int  $id
     * @param  mixed  $createdAt
     * @return mixed
     */
    private function generatePasswordSalt($id, $createdAt)
    {
        $salt = $createdAt ^ $id;
        $salt = ($salt >> 4) | ($salt << 4);

        return $salt;
    }

    /**
     * generatePasswordHash
     *
     * @param  int  $id
     * @param  mixed  $createdAt
     * @param  string  $password
     * @return string
     */
    private function generatePasswordHash($id, $createdAt, $password)
    {
        // generate salt
        $salt = $this->generatePasswordSalt($id, $createdAt);

        // generate hash
        return hash(config('auth.passwords.algorithm'), $password . $salt);
    }
}
