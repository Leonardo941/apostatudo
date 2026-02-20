<?php

namespace Tests\Feature;

use App\Models\Nivel;
use App\Models\Profissional;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class ProfissionalControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private string $adminToken;
    private string $userToken;
    private Nivel $nivel;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['nome' => 'ADMIN']);
        Role::create(['nome' => 'USER']);

        $this->admin = User::factory()->admin()->create();
        $this->user = User::factory()->create();

        $this->adminToken = JWTAuth::fromUser($this->admin);
        $this->userToken = JWTAuth::fromUser($this->user);

        $this->nivel = Nivel::create(['nivel' => 'Junior']);
    }

    private function authHeader(string $token): array
    {
        return ['Authorization' => "Bearer $token"];
    }

    public function test_list_profissionais_authenticated(): void
    {
        $response = $this->getJson('/api/profissionais', $this->authHeader($this->userToken));

        $response->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_list_profissionais_unauthenticated(): void
    {
        $response = $this->getJson('/api/profissionais');

        $response->assertUnauthorized();
    }

    public function test_create_profissional_as_admin(): void
    {
        $response = $this->postJson('/api/profissionais', [
            'nivel_id' => $this->nivel->id,
            'nome' => 'Maria Silva',
            'sexo' => 'F',
            'data_nascimento' => '1995-05-15',
            'hobby' => 'Leitura',
        ], $this->authHeader($this->adminToken));

        $response->assertCreated()
            ->assertJsonFragment(['nome' => 'Maria Silva']);

        $this->assertDatabaseHas('profissionais', ['nome' => 'Maria Silva']);
    }

    public function test_create_profissional_as_user(): void
    {
        $response = $this->postJson('/api/profissionais', [
            'nivel_id' => $this->nivel->id,
            'nome' => 'Maria Silva',
            'sexo' => 'F',
            'data_nascimento' => '1995-05-15',
        ], $this->authHeader($this->userToken));

        $response->assertForbidden();
    }

    public function test_create_profissional_validation(): void
    {
        $response = $this->postJson('/api/profissionais', [
            'nome' => 'Maria',
        ], $this->authHeader($this->adminToken));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nivel_id', 'sexo', 'data_nascimento']);
    }

    public function test_update_profissional(): void
    {
        $profissional = Profissional::create([
            'nivel_id' => $this->nivel->id,
            'nome' => 'João',
            'sexo' => 'M',
            'data_nascimento' => '1990-01-01',
        ]);

        $response = $this->putJson("/api/profissionais/{$profissional->id}", [
            'nivel_id' => $this->nivel->id,
            'nome' => 'João Atualizado',
            'sexo' => 'M',
            'data_nascimento' => '1990-01-01',
        ], $this->authHeader($this->adminToken));

        $response->assertOk()
            ->assertJsonFragment(['nome' => 'João Atualizado']);
    }

    public function test_delete_profissional(): void
    {
        $profissional = Profissional::create([
            'nivel_id' => $this->nivel->id,
            'nome' => 'João',
            'sexo' => 'M',
            'data_nascimento' => '1990-01-01',
        ]);

        $response = $this->deleteJson(
            "/api/profissionais/{$profissional->id}",
            [],
            $this->authHeader($this->adminToken)
        );

        $response->assertNoContent();
        $this->assertDatabaseMissing('profissionais', ['id' => $profissional->id]);
    }

    public function test_show_profissional(): void
    {
        $profissional = Profissional::create([
            'nivel_id' => $this->nivel->id,
            'nome' => 'João',
            'sexo' => 'M',
            'data_nascimento' => '1990-01-01',
        ]);

        $response = $this->getJson(
            "/api/profissionais/{$profissional->id}",
            $this->authHeader($this->userToken)
        );

        $response->assertOk()
            ->assertJsonFragment(['nome' => 'João']);
    }
}
