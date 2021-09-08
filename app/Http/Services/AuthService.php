<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class AuthService
{
    public function createToken(User $user, string $device = null)
    {
        return $user->createToken($device ?? 'app_token')->plainTextToken;
    }

    /**
     * @throws ValidationException
     */
    public function login(string $email, string $password): string
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return $this->createToken($user);
    }

    public function registration(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }
}