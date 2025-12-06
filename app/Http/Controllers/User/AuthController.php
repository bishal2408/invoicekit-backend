<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Services\User\AuthService;
use App\Traits\Response;

class AuthController extends Controller
{
    use Response;

    /**
     * authService
     *
     * @var AuthService
     */
    private $authService;

    /**
     * __construct
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * login
     *
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        $email = $request->validated('email');
        $password = $request->validated('password');

        $token = $this->authService->login($email, $password);

        if ($token) {
            return $this->sendResponseWithData([
                'access_token' => $token,
            ], 'LOGIN_SUCCESS');
        }

        return $this->errorResponse('LOGIN_FAILED');
    }

    /**
     * logout
     *
     * @return mixed
     */
    public function logout()
    {
        $user = auth('sanctum')->user();

        $response = $this->authService->logout($user);

        if ($response) {
            return $this->sendResponse('LOGOUT_SUCCESS');
        }

        return $this->errorResponse('LOGOUT_FAILED');
    }
}
