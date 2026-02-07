<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Посев тестовых пользователей в базу данных
     */
    public function run(): void
    {
        {
            $this->call([
                UserSeeder::class,
            ]);
        }
    }
}
