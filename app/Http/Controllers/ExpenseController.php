<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function index()
    {
        return Expense::with('category')->where('user_id', Auth::id())->get();  // Get all expenses for the logged-in user
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric', // Add validation rules for other fields
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id', // Make sure category exists
            'expense_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Return 422 with errors
        }

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

    /**
     * Get categorized expense summary for a specified period.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCategorizedSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Return 422 with errors
        }

        $userId = Auth::id();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $summary = Expense::with('category')
            ->where('user_id', $userId)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->get()
            ->groupBy('category.name') // Group by category name
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category->name, // Get the category name
                    'total_amount' => $group->sum('amount'), // Sum the amounts
                ];
            })->values(); // Reset the keys to be sequential

        return response()->json($summary);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->noContent();
    }
}
