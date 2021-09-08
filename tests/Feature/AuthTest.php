<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Symfony\Component\HttpFoundation\Response;

final class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @covers \App\Http\Controllers\AuthController::registration
     */
    public function test_registration()
    {
        $password = $this->faker->password();
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->post('/api/registration', $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'user' => [
                'name' => $data['name'],
                'email' => $data['email'],
            ]
        ]);
    }

    /**
     * @covers \App\Http\Controllers\AuthController::login
     */
    public function test_login()
    {
        $password = $this->faker->password();
        $user = User::factory()->create(['password' => Hash::make($password)]);

        $data = ['email' => $user->email, 'password' => $password];
        $response = $this->post('/api/login', $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['token']);
    }
}
