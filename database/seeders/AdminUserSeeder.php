<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Создаём админа, если его ещё нет
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'], // проверка по email
            [
                'name' => 'Администратор',
                'password' => Hash::make('11111111'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
