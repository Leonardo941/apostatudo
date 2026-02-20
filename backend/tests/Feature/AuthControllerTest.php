<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['nome' => 'ADMIN']);
        Role::create(['nome' => 'USER']);

        $this->admin = User::factory()->admin()->create([
            'email' => 'admin@test.com',
        ]);

        $this->user = User::factory()->create([
            'email' => 'user@test.com',
        ]);
    }

    public function test_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJsonMissing(['refresh_token'])
            ->assertCookie('refresh_token');
    }

    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong',
        ]);

        $response->assertUnauthorized()
            ->assertJson(['message' => 'Credenciais inválidas.']);
    }

    public function test_login_validates_required_fields(): void
    {
        $response = $this->postJson('/api/auth/login', []);

        $response->assertStatus(422);
    }

    public function test_refresh_with_valid_cookie(): void
    {
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'user@test.com',
            'password' => 'password',
        ]);

        // Extract refresh_token cookie value from Set-Cookie header
        $cookies = $loginResponse->headers->getCookies();
        $refreshValue = null;
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === 'refresh_token') {
                $refreshValue = $cookie->getValue();
                break;
            }
        }

        $this->assertNotNull($refreshValue, 'Refresh token cookie not found');

        $response = $this->call('POST', '/api/auth/refresh', [], [
            'refresh_token' => $refreshValue,
        ], [], ['HTTP_ACCEPT' => 'application/json']);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function test_refresh_without_cookie(): void
    {
        $response = $this->postJson('/api/auth/refresh');

        $response->assertUnauthorized();
    }

    public function test_logout_authenticated(): void
    {
        $token = JWTAuth::fromUser($this->admin);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/auth/logout');

        $response->assertOk()
            ->assertJson(['message' => 'Logout realizado com sucesso.']);
    }

    public function test_logout_unauthenticated(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertUnauthorized();
    }

    public function test_login_rate_limiting(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/auth/login', [
                'email' => 'admin@test.com',
                'password' => 'wrong',
            ]);
        }

        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(429);
    }
}
