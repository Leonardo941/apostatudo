<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_health_check(): void
    {
        $response = $this->get('/up');

        $response->assertOk();
    }
}
