<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExpenseController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Expense Routes (add or modify these lines)
    Route::get('/expenses', function () {
        return view('expenses.index');
    })->name('expenses.index');
    Route::get('/expenses/add', function () {
        return view('expenses.add');
    })->name('expenses.add');
    Route::get('/expenses/{expense}/edit', function () {
        return view('expenses.edit');
    })->name('expenses.edit');
    Route::get('/categories', function () {
        return view('categories.index');
    })->name('categories.index');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update'); // Update route
});

// Route::get('/expenses', function () {
//     return view('expenses.index'); // Display expenses list
// });

// Route::get('/expenses/add', function () {
//     return view('expenses.add-expense'); // Display the add expense form
// });



require __DIR__ . '/auth.php';
