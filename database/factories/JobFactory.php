<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Job;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'company' => fake()->company(),
            'location' => fake()->city(),
            'description' => fake()->paragraph(),
            'is_published' => fake()->boolean(80), // 80% chance of being published
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now()
        ];
    }
}
