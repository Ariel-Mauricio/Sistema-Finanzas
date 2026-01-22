<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@finanzapro.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('Admin@2026#Secure'),
                'role' => 'admin',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario contador
        \App\Models\User::updateOrCreate(
            ['email' => 'contador@finanzapro.com'],
            [
                'name' => 'Contador',
                'password' => bcrypt('Contador@2026#'),
                'role' => 'contador',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        echo "Usuarios creados:\n";
        echo "1. admin@finanzapro.com / Admin@2026#Secure (admin)\n";
        echo "2. contador@finanzapro.com / Contador@2026# (contador)\n";
    }
}
