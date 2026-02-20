<?php

namespace Tests\Feature;

use App\Models\Nivel;
use App\Models\Profissional;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class NivelControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private string $adminToken;
    private string $userToken;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['nome' => 'ADMIN']);
        Role::create(['nome' => 'USER']);

        $this->admin = User::factory()->admin()->create();
        $this->user = User::factory()->create();

        $this->adminToken = JWTAuth::fromUser($this->admin);
        $this->userToken = JWTAuth::fromUser($this->user);
    }

    private function authHeader(string $token): array
    {
        return ['Authorization' => "Bearer $token"];
    }

    public function test_list_niveis_authenticated(): void
    {
        Nivel::create(['nivel' => 'Junior']);

        $response = $this->getJson('/api/niveis', $this->authHeader($this->adminToken));

        $response->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_list_niveis_unauthenticated(): void
    {
        $response = $this->getJson('/api/niveis');

        $response->assertUnauthorized();
    }

    public function test_create_nivel_as_admin(): void
    {
        $response = $this->postJson('/api/niveis', [
            'nivel' => 'Senior',
        ], $this->authHeader($this->adminToken));

        $response->assertCreated()
            ->assertJsonFragment(['nivel' => 'Senior']);

        $this->assertDatabaseHas('niveis', ['nivel' => 'Senior']);
    }

    public function test_create_nivel_as_user(): void
    {
        $response = $this->postJson('/api/niveis', [
            'nivel' => 'Senior',
        ], $this->authHeader($this->userToken));

        $response->assertForbidden();
    }

    public function test_create_duplicate_nivel(): void
    {
        Nivel::create(['nivel' => 'Junior']);

        $response = $this->postJson('/api/niveis', [
            'nivel' => 'Junior',
        ], $this->authHeader($this->adminToken));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nivel']);
    }

    public function test_update_nivel_as_admin(): void
    {
        $nivel = Nivel::create(['nivel' => 'Junior']);

        $response = $this->putJson("/api/niveis/{$nivel->id}", [
            'nivel' => 'Pleno',
        ], $this->authHeader($this->adminToken));

        $response->assertOk()
            ->assertJsonFragment(['nivel' => 'Pleno']);
    }

    public function test_update_nivel_with_same_name(): void
    {
        $nivel = Nivel::create(['nivel' => 'Junior']);

        $response = $this->putJson("/api/niveis/{$nivel->id}", [
            'nivel' => 'Junior',
        ], $this->authHeader($this->adminToken));

        $response->assertOk();
    }

    public function test_delete_nivel_without_profissionais(): void
    {
        $nivel = Nivel::create(['nivel' => 'Junior']);

        $response = $this->deleteJson("/api/niveis/{$nivel->id}", [], $this->authHeader($this->adminToken));

        $response->assertNoContent();
        $this->assertDatabaseMissing('niveis', ['id' => $nivel->id]);
    }

    public function test_delete_nivel_with_profissionais(): void
    {
        $nivel = Nivel::create(['nivel' => 'Junior']);
        Profissional::create([
            'nivel_id' => $nivel->id,
            'nome' => 'João',
            'sexo' => 'M',
            'data_nascimento' => '1990-01-01',
        ]);

        $response = $this->deleteJson("/api/niveis/{$nivel->id}", [], $this->authHeader($this->adminToken));

        $response->assertStatus(409);
    }

    public function test_show_nivel(): void
    {
        $nivel = Nivel::create(['nivel' => 'Junior']);

        $response = $this->getJson("/api/niveis/{$nivel->id}", $this->authHeader($this->adminToken));

        $response->assertOk()
            ->assertJsonFragment(['nivel' => 'Junior']);
    }

    public function test_show_nonexistent_nivel(): void
    {
        $response = $this->getJson('/api/niveis/999', $this->authHeader($this->adminToken));

        $response->assertNotFound();
    }
}
