<?php

namespace App\Http\Controllers\Api;

use App\Models\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return Expense::with('category')->where('user_id', Auth::id())->get();
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
}
