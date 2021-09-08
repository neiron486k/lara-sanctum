<?php

declare(strict_types=1);

namespace Tests\Feature;

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
}
