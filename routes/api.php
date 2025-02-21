<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->post('/expenses', [ExpenseController::class, 'store']);
Route::middleware('auth:sanctum')->post('/categories', [CategoryController::class, 'store']);
