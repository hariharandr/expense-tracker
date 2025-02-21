<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User; // Import
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(10, 1000),
            'description' => $this->faker->sentence,
            'expense_date' => $this->faker->date(),
            'user_id' => User::factory(), // CRUCIAL
            'category_id' => Category::factory(), 
        ];
    }
}
