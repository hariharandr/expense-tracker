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
    $data = []; // No 'name' provided

    $response = $this->actingAs($this->user)
        ->postJson('/api/categories', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('name');
});


test('user can update a category', function () {
    $user = User::factory()->create(); // Create a user
    $category = Category::factory()->create(['user_id' => $user->id]); // Create a category and assign it to the user

    $updatedData = [
        'name' => 'Updated Category Name',
    ];

    $response = $this->actingAs($user)->putJson("/api/categories/{$category->id}", $updatedData);

    $response->assertStatus(200); // Should be 200 now
    $response->assertJsonFragment($updatedData);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => $updatedData['name'],
        'user_id' => $user->id, // Important: Check user_id in database
    ]);
});

test('user can delete a category', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)
        ->delete('/api/categories/' . $category->id, []);

    $response->assertStatus(204);
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
