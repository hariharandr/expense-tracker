<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        return Expense::with('category')->where('user_id', Auth::id())->get();  // Get all expenses for the logged-in user
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        if (Auth::check()) {
            $expense = Expense::create([
                'user_id' => Auth::id(),  // Ensure the user is authenticated
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'expense_date' => $request->expense_date,
            ]);

            return response()->json($expense, 201);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function show($expense)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($expense);
        return response()->json($expense);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return response()->json($expense);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->noContent();
    }
}
