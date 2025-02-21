<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $category = Category::create($request->all());

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->noContent();
    }
}
