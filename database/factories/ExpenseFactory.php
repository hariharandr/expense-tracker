<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // create one category if it doesn't exist
        if (!Category::count()) {
            Category::factory()->create();
        }

        return [
            'amount' => $this->faker->numberBetween(100, 5000),
            'description' => $this->faker->sentence(),
            'category_id' => Category::factory(),
            'user_id' => \App\Models\User::factory(),
            'expense_date' => $this->faker->date(),
        ];
    }
}
