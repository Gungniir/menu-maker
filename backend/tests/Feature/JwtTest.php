<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTProvider;
use Tests\TestCase;

class JwtTest extends TestCase
{
    public function test_login(): void
    {
        $user = User::factory()->state([
            'email' => 'test@menumaker.ru',
            'password' => \Hash::make('password')
        ])->create();

        $response = $this->post('/api/auth/login', [
            'email' => 'test@menumaker.ru',
            'password' => 'password'
        ]);

        $user->forceDelete();

        $response->assertStatus(200);
    }

    public function test_logout(): void
    {
        $user = User::factory()->state([
            'email' => 'test@menumaker.ru',
            'password' => \Hash::make('password')
        ])->create();

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $jwt = auth()->login($user);

        $response = $this->post('/api/auth/logout', [], [
            'Authorization' => "Bearer $jwt"
        ]);

        $user->forceDelete();

        $response->assertStatus(200);
    }

    public function test_acting_as_user(): void
    {
        $user = User::factory()->state([
            'email' => 'test@menumaker.ru',
            'password' => \Hash::make('password')
        ])->create();

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $jwt = auth()->login($user);

        $response = $this->post('/api/auth/me', [], [
            'Authorization' => "Bearer $jwt"
        ]);

        $user->forceDelete();

        $response->assertStatus(200);
        $response->assertJson($user->toArray());
    }

    public function test_acting_as_guest(): void
    {
        $user = User::factory()->state([
            'email' => 'test@menumaker.ru',
            'password' => \Hash::make('password')
        ])->create();

        $response = $this->postJson('/api/auth/me');

        $user->forceDelete();

        $response->assertStatus(401);
    }
}
