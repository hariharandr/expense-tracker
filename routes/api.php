<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Category Routes
    Route::resource('categories', CategoryController::class);

    // Expense Routes
    Route::resource('expenses', ExpenseController::class);
});
