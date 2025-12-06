<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Services\User\AuthService;

class AuthController extends Controller
{
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

        return response()->json([
            'access_token' => $token,
        ]);
    }

    /**
     * logout
     *
     * @return mixed
     */
    public function logout()
    {
        $user = auth('sanctum')->user();

        $this->authService->logout($user);

        return response()->json([
            'message' => 'Logout Successful',
        ], 200);
    }
}
