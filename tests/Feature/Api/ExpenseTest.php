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
    $category = Category::factory()->create(); // Ensure category exists

    $data = [
        'category_id' => $category->id,
        'expense_date' => '2025-02-21',
    ];

    $response = $this->actingAs($this->user)
        ->postJson('/api/expenses', $data); // Use postJson for JSON requests

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['amount']);
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

test('user can fetch categorized expense summary', function () {
    // Create categories and expenses
    $category1 = Category::factory()->create(['name' => 'Food']);
    $category2 = Category::factory()->create(['name' => 'Transport']);

    Expense::factory()->create([
        'user_id' => $this->user->id,
        'category_id' => $category1->id,
        'amount' => 100,
        'expense_date' => now()->toDateString(),
    ]);
    Expense::factory()->create([
        'user_id' => $this->user->id,
        'category_id' => $category2->id,
        'amount' => 200,
        'expense_date' => now()->toDateString(),
    ]);

    // Make the API request
    $response = $this->actingAs($this->user)->get('/api/expense-summary?start_date=' . now()->toDateString() . '&end_date=' . now()->toDateString());

    // Assert the response status and structure
    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['category', 'total_amount']
        ])
        ->assertJson([
            ['category' => 'Food', 'total_amount' => 100],
            ['category' => 'Transport', 'total_amount' => 200],
        ]);
});

test('user cannot fetch expense summary without authentication', function () {
    // Make the API request without authentication
    $response = $this->getJson('/api/expense-summary?start_date=' . now()->toDateString() . '&end_date=' . now()->toDateString());

    // Assert the response status is 401 (Unauthorized)
    $response->assertStatus(401);
});

test('user cannot fetch expense summary with invalid dates', function () {
    $response = $this->actingAs($this->user)->get('/api/expense-summary?start_date=invalid-date&end_date=invalid-date');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_date', 'end_date']);
});
