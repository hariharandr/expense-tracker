<?php

use App\Models\Expense;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    // Create a user and generate an API token for authentication
    $this->user = User::factory()->create();
});

test('user can create a new expense', function () {
    // Create a category to associate with the expense
    $category = Category::factory()->create();

    $data = [
        'amount' => 1000,
        'description' => 'Test expense',
        'category_id' => $category->id, // Use the created category's ID
        'expense_date' => '2025-02-21',
    ];

    $response = $this->actingAs($this->user)
        ->post('/api/expenses', $data);

    $response->assertStatus(201);
    $response->assertJsonFragment($data);
});


test('user can fetch expenses', function () {
    // Create a category and an expense associated with the user
    $category = Category::factory()->create();
    $expense = Expense::factory()->create([
        'user_id' => $this->user->id, // Ensure the expense is associated with the authenticated user
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get('/api/expenses');

    $response->assertStatus(200);
    $response->assertJsonFragment(['description' => $expense->description]);
});


test('user cannot create an expense without amount', function () {
    // Create a category to associate with the expense
    $category = Category::factory()->create();

    $data = [
        'category_id' => $category->id, // Ensure valid category_id
        'expense_date' => '2025-02-21',
        // 'amount' field is deliberately omitted to trigger validation error
    ];

    $response = $this->actingAs($this->user) // Ensure the user is authenticated
        ->post('/api/expenses', $data);

    $response->assertStatus(422); // Expecting a validation error
    $response->assertJsonValidationErrors(['amount']); // Ensure that the 'amount' field is validated
});



test('user can update an expense', function () {
    $expense = Expense::factory()->create();
    $updatedData = [
        'amount' => 1500,
        'description' => 'Updated Expense',
    ];

    $response = $this->actingAs($this->user)
        ->put('/api/expenses/' . $expense->id, $updatedData);

    $response->assertStatus(200);
    $response->assertJsonFragment($updatedData);
});

test('user can delete an expense', function () {
    $expense = Expense::factory()->create();

    $response = $this->actingAs($this->user)
        ->delete('/api/expenses/' . $expense->id, []);

    $response->assertStatus(204);
    $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
});
