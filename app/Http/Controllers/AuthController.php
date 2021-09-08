<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Services\AuthService;

final class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): array
    {
        $validated = $request->validated();
        $token = $this->authService->login($validated['email'], $validated['password']);

        return ['token' => $token];
    }

    public function logout(): array
    {
        auth()->user()->tokens()->delete();

        return ['message' => 'Logged out'];
    }

    public function registration(RegistrationRequest $request): array
    {
        $validated = $request->validated();
        $user = $this->authService->registration($validated);

        return [
            'user' => $user,
            'token' => $this->authService->createToken($user)
        ];
    }
}
