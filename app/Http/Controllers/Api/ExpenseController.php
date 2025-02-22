<?php

namespace App\Http\Controllers\Api;

use App\Models\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Expense::with('category')->where('user_id', Auth::id());

        // Filter by date if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'expense_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $expense = new Expense($request->all());
        $expense->user_id = Auth::id();
        $expense->save();

        return response()->json($expense, 201);
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense); // Check if user is authorized to view this expense
        return response()->json($expense);
    }

    public function edit($id)
    {
        try {
            $expense = Expense::findOrFail($id); // Find the expense (findOrFail is KEY)

            // Authorization Check (HIGHLY RECOMMENDED for security):
            if ($expense->user_id !== Auth::id()) { // Use Auth facade and check user_id
                abort(403, 'Unauthorized action.'); // Or redirect, or show a message
            }

            $categories = Category::all(); // Fetch all categories for the dropdown

            return view('expenses.edit', compact('expense', 'categories')); // Pass both variables!
            // OR, you can use:
            // return view('expenses.edit')->with('expense', $expense)->with('categories', $categories);

        } catch (\Exception $e) {
            // Handle exceptions (log and/or display an error message):
            // \Log::error($e); // Log the exception for debugging
            // return redirect()->route('expenses.index')->with('error', 'Expense not found.'); // Example redirect
            abort(404, 'Expense not found.'); // Or display a 404 page
        }
    }

    public function update(Request $request, Expense $expense)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'expense_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($expense->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $expense->update($request->all());
        return response()->json($expense);
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $expense->delete();
        return response()->noContent();
    }



    public function getCategorizedSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = Auth::id();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $summary = Expense::with('category')
            ->where('user_id', $userId)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->get()
            ->groupBy('category.name')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category->name,
                    'total_amount' => $group->sum('amount'),
                ];
            })->values();

        return response()->json($summary);
    }

    public function add()
    {
        // Your logic for displaying the add expense form
        return view('expenses.add-expense');
    }
}
