<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nome', 'ADMIN')->first();
        $userRole = Role::where('nome', 'USER')->first();

        User::firstOrCreate(
            ['email' => 'admin@apostatudo.com'],
            [
                'nome' => 'Administrador',
                'password' => bcrypt('password'),
                'role_id' => $adminRole->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@apostatudo.com'],
            [
                'nome' => 'Usuario',
                'password' => bcrypt('password'),
                'role_id' => $userRole->id,
            ]
        );
    }
}
