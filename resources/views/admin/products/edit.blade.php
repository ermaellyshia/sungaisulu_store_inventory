@extends('layouts.app')

@section('title', 'Edit Product')
@section('header', 'Edit Product')
@section('subheader', 'Update product information')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="bg-purple-50 dark:bg-gray-800 rounded-2xl shadow-lg p-8">
        
        <!-- Back Button -->
        <a href="{{ route('admin.products') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 mb-6 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Products
        </a>
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Product</h2>
        
        <!-- Error Display -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Please fix the following errors:</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- FORM -->
        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-5">
                
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="product_name" value="{{ old('product_name', $product->product_name) }}" required
                           class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                           placeholder="Enter product name">
                    @error('product_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Price -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Price (RM) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">RM</span>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                               class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                               placeholder="0.00">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Category
                    </label>
                    <select name="category_id" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Stock & Min Stock -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Stock Quantity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                               class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                               placeholder="0">
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Minimum Stock Alert <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required
                               class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                               placeholder="5">
                        @error('min_stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Product Picture -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Product Picture
                    </label>
                    
                    <!-- Show current image -->
                    @if($product->product_picture)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $product->product_picture) }}" 
                                 alt="{{ $product->product_name }}" 
                                 class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 mt-1">Current image</p>
                        </div>
                    @endif
                    
                    <input type="file" name="product_picture" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB. Supported: jpeg, png, jpg, gif. Leave blank to keep current image.</p>
                    @error('product_picture')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <a href="{{ route('admin.products') }}"
                       class="flex-1 text-center border border-gray-300 dark:border-gray-600 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
                        Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection