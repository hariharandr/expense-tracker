<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    // Category Routes
    Route::resource('categories', CategoryController::class);

    // Expense Routes
    Route::resource('expenses', ExpenseController::class);
    Route::get('/expense-summary', [ExpenseController::class, 'getCategorizedSummary']);
});
