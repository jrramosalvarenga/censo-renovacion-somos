<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@renovacionsomos.hn'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('Renovacion2024!'),
            ]
        );
    }
}
