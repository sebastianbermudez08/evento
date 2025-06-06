<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@evento.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@evento.com',
                'password' => Hash::make('admin123'), // puedes cambiar la contraseña
            ]
        );
    }
}
