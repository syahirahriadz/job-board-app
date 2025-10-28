<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'aida@gmail.com'],
            [
                'name' => 'Aida',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => bcrypt('test123'),
            ]
        );

        User::factory(10)->create();
    }
}
