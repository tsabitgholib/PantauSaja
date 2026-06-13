<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User 1
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // User 2
        User::updateOrCreate(
            ['username' => 'user'],
            [
                'name' => 'User ',
                'password' => Hash::make('password'),
            ]
        );
    }
}
