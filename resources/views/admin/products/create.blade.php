@extends('layouts.app')

@section('title', 'Add New Product')
@section('header', 'Add New Product')
@section('subheader', 'Create a new product for your inventory')

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
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Product Information</h2>
        
        <!-- FORM - TAMBAH enctype="multipart/form-data" -->
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-5">
                
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="product_name" id="product_name" required
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
                        <input type="number" step="0.01" name="price" id="price" required
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
                    <select name="category_id" id="category_id"
                            class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
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
                        <input type="number" name="stock" id="stock" required
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
                        <input type="number" name="min_stock" id="min_stock" required
                               class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                               placeholder="5">
                        @error('min_stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- ======================== -->
                <!-- PRODUCT PICTURE (NEW)     -->
                <!-- ======================== -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Product Picture
                    </label>
                    <input type="file" name="product_picture" id="product_picture" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB. Supported: jpeg, png, jpg, gif</p>
                    @error('product_picture')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Preview Section -->
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                    <div class="flex justify-between items-center">
                        <div>
                            <p id="preview_name" class="font-medium text-gray-800 dark:text-white">-</p>
                            <p id="preview_category" class="text-sm text-gray-500 dark:text-gray-400">-</p>
                        </div>
                        <div class="text-right">
                            <p id="preview_price" class="text-xl font-bold text-indigo-600">RM 0.00</p>
                            <p id="preview_stock" class="text-sm text-gray-500">Stock: 0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <a href="{{ route('admin.products') }}"
                       class="flex-1 text-center border border-gray-300 dark:border-gray-600 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
                        Save Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Live preview
    const productName = document.getElementById('product_name');
    const price = document.getElementById('price');
    const stock = document.getElementById('stock');
    const categorySelect = document.getElementById('category_id');
    
    const categories = @json($categories->map(function($cat) {
        return ['id' => $cat->id, 'name' => $cat->name];
    }));
    
    function updatePreview() {
        const name = productName.value || '-';
        const priceValue = parseFloat(price.value) || 0;
        const stockValue = stock.value || 0;
        
        const selectedCat = categories.find(c => c.id == categorySelect.value);
        const categoryName = selectedCat ? selectedCat.name : '-';
        
        document.getElementById('preview_name').textContent = name;
        document.getElementById('preview_category').textContent = categoryName;
        document.getElementById('preview_price').textContent = 'RM ' + priceValue.toFixed(2);
        document.getElementById('preview_stock').textContent = 'Stock: ' + stockValue;
    }
    
    productName.addEventListener('input', updatePreview);
    price.addEventListener('input', updatePreview);
    stock.addEventListener('input', updatePreview);
    categorySelect.addEventListener('change', updatePreview);
    
    updatePreview();
</script>
@endsection