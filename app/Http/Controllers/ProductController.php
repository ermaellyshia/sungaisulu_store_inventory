<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer',
            'min_stock' => 'required|integer',
        ]);

        Product::create($validated);

        return response()->json(['message' => 'Product created successfully']);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'product_name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'sometimes|integer',
            'min_stock' => 'sometimes|integer',
        ]);

        $product->update($validated);

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

    public function isLowStock(Product $product)
    {
        return $product->stock <= $product->min_stock;
    }
}