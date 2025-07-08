<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => rand(1, 10), // giả sử có 10 user
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'category' => fake()->optional()->word(),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['Completed', 'Pending', 'Inprogress', 'New']),
            'priority' => fake()->randomElement(['Medium', 'Low', 'High']),
        ];
    }
}