<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {}

    public function register(RegisterRequest $request)
    {

        $user  = $this->authRepository->register($request->validated());
        $token = $user->createToken('auth_token')->accessToken;

        return $this->success([
            'token' => $token,
            'user'  => $user,
        ], 'Registered successfully.', 201);
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->authRepository->login($request->only('email', 'password'));
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 401);
        }

        return $this->success([
            'token' => $token,
            'user'  => auth()->user(),
        ], 'Login successful.');
    }

    public function logout(Request $request)
    {
        $this->authRepository->logout();

        return $this->success(null, 'Logged out successfully.');
    }
}