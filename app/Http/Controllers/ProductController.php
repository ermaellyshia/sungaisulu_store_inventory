<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function getAllProducts()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    public function getProduct($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer',
            'min_stock' => 'required|integer',
            'product_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // TAMBAH INI
        ]);

        // Upload gambar kalau ada
        if ($request->hasFile('product_picture')) {
            $path = $request->file('product_picture')->store('products', 'public');
            $validated['product_picture'] = $path;
        }
        
        Product::create($validated);

        return redirect()->route('admin.products')->with('success', 'Product created successfully');
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
            'product_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // TAMBAH INI
        ]);

        // Upload gambar baru kalau ada
        if ($request->hasFile('product_picture')) {
            // Hapus gambar lama kalau ada
            if ($product->product_picture) {
                \Storage::disk('public')->delete($product->product_picture);
            }
            $path = $request->file('product_picture')->store('products', 'public');
            $validated['product_picture'] = $path;
        }

        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar kalau ada
        if ($product->product_picture) {
            \Storage::disk('public')->delete($product->product_picture);
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
    }

    public function isLowStock(Product $product)
    {
        return $product->stock <= $product->min_stock;
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function edit($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }
}