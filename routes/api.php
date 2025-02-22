    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\CategoryController;
    use App\Http\Controllers\Api\ExpenseController;

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');


    Route::middleware('auth:sanctum')->group(function () {
        // Category Routes
        // pass user auth as a parameter with the requeest 
        Route::resource('categories', CategoryController::class);
        Route::post('/api/categories', [CategoryController::class, 'store']);

        // Expense Routes
        Route::resource('expenses', ExpenseController::class);
        Route::get('/expense-summary', [ExpenseController::class, 'getCategorizedSummary']);
        Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        // Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update'); // Update route
        Route::put('/expenses/{expense}', [ExpenseController::class, 'update']);
    });
