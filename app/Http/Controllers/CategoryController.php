<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getAllCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
    
    public function index()
    {
        $categories = Category::with('products')->get();
        return view('admin.categories.index', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $category = Category::create($validated);
        
        return response()->json(['message' => 'Category created successfully', 'category' => $category]);
    }
    
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $category->update($validated);
        
        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }
    
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return response()->json(['message' => 'Category deleted successfully']);
    }
}