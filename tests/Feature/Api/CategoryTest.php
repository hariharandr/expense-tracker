<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    // Create a user and generate an API token for authentication
    $this->user = User::factory()->create();
    // $this->token = $this->user->createToken('TestToken')->plainTextToken();
});

test('user can create a new category', function () {
    $data = [
        'name' => 'New Category',
    ];

    $response = $this->actingAs($this->user)
        ->post('/api/categories', $data);

    $response->assertStatus(201);
    $response->assertJsonFragment($data);
});

test('user can fetch categories', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)
        ->get('/api/categories');

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => $category->name]);
});


test('user cannot create a category without name', function () {
    $data = []; // No name provided to trigger validation error

    $response = $this->actingAs($this->user) // Ensure the user is authenticated
        ->post('/api/categories', $data);

    $response->assertStatus(422); // Expecting a validation error due to missing name
    $response->assertJsonValidationErrors('name'); // Assert that the 'name' field has a validation error
});


test('user can update a category', function () {
    $category = Category::factory()->create();
    $updatedData = [
        'name' => 'Updated Category Name',
    ];

    $response = $this->actingAs($this->user)
        ->put('/api/categories/' . $category->id, $updatedData);

    $response->assertStatus(200);
    $response->assertJsonFragment($updatedData);
});

test('user can delete a category', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)
        ->delete('/api/categories/' . $category->id, []);

    $response->assertStatus(204);
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
