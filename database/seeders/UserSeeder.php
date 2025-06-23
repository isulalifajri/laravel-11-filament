<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'ADMIN',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'USER',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Edit User',
            'email' => 'edit@example.com',
            'role' => 'EDITOR',
            'password' => Hash::make('password'),
        ]);

        User::factory(1000)->create();
    }
}
