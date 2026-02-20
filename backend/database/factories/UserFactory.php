<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role_id' => Role::firstOrCreate(['nome' => 'USER'])->id,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::firstOrCreate(['nome' => 'ADMIN'])->id,
        ]);
    }
}
