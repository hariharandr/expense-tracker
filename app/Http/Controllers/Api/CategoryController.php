<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = new Category($validator);
        $category->user_id = Auth::id(); // <-- Crucial: Set the user_id
        $category->save();

        return response()->json($category, 201); // 201 Created status code
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,' . $category->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($request->only('name'));

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
