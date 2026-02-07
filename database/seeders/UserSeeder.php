<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Тестовые пользователи с разными ролями
     */
    public function run(): void
    {
        User::factory(1)->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
        ]);

        User::factory(1)->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('userpassword'),
            'role' => 'user',
            'created_at' => now(),
        ]);
    }
}
