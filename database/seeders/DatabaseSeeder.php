<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Job;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        Job::query()->delete();

        // Seed users first
        $this->call([UserSeeder::class]);

        // Create jobs and assign some to employers
        $employers = User::where('role', 'employer')->get();

        if ($employers->isNotEmpty()) {
            // Create jobs with employers
            Job::factory(10)->create([
                'user_id' => fn() => $employers->random()->id,
            ]);
        }

        // Create some jobs without assigned employers
        Job::factory(5)->create();
    }
}
