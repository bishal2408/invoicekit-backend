<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;
use App\Traits\PasswordTrait;
use Illuminate\Support\Facades\Log;

class AuthService
{
    use PasswordTrait;

    /**
     * userRepository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * __construct
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * login
     *
     * @param  mixed  $email
     * @param  mixed  $password
     * @return mixed
     */
    public function login($email, $password)
    {
        try {
            /** @var \App\Models\User\User|null $user */
            $user = $this->userRepository->findBy('email', $email);

            if ($user) {
                // generate password hash
                $passwordHash = $this->generatePasswordHash(
                    $user->id,
                    strtotime($user->created_at),
                    $password
                );

                if ($user->password == $passwordHash) {
                    return $this->generateAccessToken($user);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * logout
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function logout($user)
    {
        return $user->currentAccessToken()->delete();
    }

    /**
     * generateAccessToken
     *
     * @param  mixed  $user
     * @return mixed
     */
    private function generateAccessToken($user)
    {
        return $user->createToken(config('auth.access_token_name'))->plainTextToken;
    }
}
