<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
class RecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => fake()->randomElement(['Income', 'Expense']),
            'amount' => fake()->randomFloat(2, 100, 50000),
            'date' => fake()->date(),
            'title' => fake()->words(6, true),
            'description' => fake()->words(20, true),
            'user_id' => 1,
            'created_by' => 1,
        ];
    }
}
