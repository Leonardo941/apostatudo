<?php

namespace Tests\Unit;

use App\Models\Nivel;
use App\Models\Profissional;
use App\Models\Role;
use App\Services\NivelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Tests\TestCase;

class NivelServiceTest extends TestCase
{
    use RefreshDatabase;

    private NivelService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['nome' => 'USER']);
        $this->service = app(NivelService::class);
    }

    public function test_delete_nivel_without_profissionais(): void
    {
        $nivel = Nivel::create(['nivel' => 'Junior']);

        $result = $this->service->delete($nivel->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('niveis', ['id' => $nivel->id]);
    }

    public function test_delete_nivel_with_profissionais_throws_exception(): void
    {
        $nivel = Nivel::create(['nivel' => 'Junior']);
        Profissional::create([
            'nivel_id' => $nivel->id,
            'nome' => 'João',
            'sexo' => 'M',
            'data_nascimento' => '1990-01-01',
        ]);

        $this->expectException(ConflictHttpException::class);

        $this->service->delete($nivel->id);
    }

    public function test_delete_nonexistent_nivel(): void
    {
        $result = $this->service->delete(999);

        $this->assertFalse($result);
    }

    public function test_create_nivel(): void
    {
        $nivel = $this->service->create(['nivel' => 'Senior']);

        $this->assertEquals('Senior', $nivel->nivel);
        $this->assertDatabaseHas('niveis', ['nivel' => 'Senior']);
    }

    public function test_find_nivel(): void
    {
        $nivel = Nivel::create(['nivel' => 'Pleno']);

        $found = $this->service->find($nivel->id);

        $this->assertNotNull($found);
        $this->assertEquals('Pleno', $found->nivel);
    }

    public function test_find_nonexistent_nivel(): void
    {
        $found = $this->service->find(999);

        $this->assertNull($found);
    }
}
