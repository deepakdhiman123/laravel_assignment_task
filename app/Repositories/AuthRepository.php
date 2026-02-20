<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Register new user
     */
    public function register(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Login user and return access token
     */
    public function login(array $credentials): string
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        return auth()->user()
            ->createToken('auth_token')
            ->accessToken;
    }

    /**
     * Logout user (Passport)
     */
    public function logout(): void
    {
        $user = auth()->user();

        if ($user && $user->token()) {
            $user->token()->revoke();
        }
    }
}