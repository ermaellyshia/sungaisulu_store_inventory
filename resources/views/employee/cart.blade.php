@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-purple-700 dark:text-purple-300">Employee Dashboard - SungaiSulu Store</h1>

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('employee.cart') }}" class="mb-6">
            <div class="flex gap-4">
                <input type="text" name="search" placeholder="Find product..."
                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="{{ request('search') }}">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-black rounded-lg hover:bg-blue-700 dark:text-purple">
                    Find
                </button>
            </div>
        </form>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
        @endif

        @php
            $cart = session()->get('cart', []);
        @endphp

        @if ($products->isEmpty())
            <div class="bg-yellow-100 p-6 rounded-lg text-center">
                <p class="text-yellow-700">Product not found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                        {{-- GAMBAR DAN MAKLUMAT --}}
                        <div class="w-full text-left">
                            @if ($product->product_picture)
                                <img src="{{ asset('storage/' . $product->product_picture) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-32 object-cover rounded mb-3">
                            @endif
                            <h3 class="font-semibold text-lg">{{ $product->product_name }}</h3>
                            <p class="text-gray-600">Stock: <span
                                    class="font-bold {{ $product->stock < $product->min_stock ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $product->stock }}
                                </span></p>
                            <p class="text-gray-600">Price: RM {{ number_format($product->price, 2) }}</p>
                        </div>

                        {{-- FORM UNTUK ADD TO CART --}}
                        <form action="{{ route('employee.addToCart') }}" method="POST" id="form-{{ $product->id }}"
                            class="mt-3 pt-3 border-t border-gray-200">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="qty-{{ $product->id }}" value="1">

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="updateQuantity({{ $product->id }}, -1)"
                                        class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full text-lg font-bold transition">
                                        −
                                    </button>
                                    <span id="display-qty-{{ $product->id }}"
                                        class="w-8 text-center font-semibold text-lg">1</span>
                                    <button type="button" onclick="updateQuantity({{ $product->id }}, 1)"
                                        class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full text-lg font-bold transition">
                                        +
                                    </button>
                                </div>
                                <button type="submit"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-semibold">
                                    Add
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- Shopping Cart (Float) --}}
    <div id="cartPanel"
        class="fixed bottom-0 right-0 w-96 bg-white shadow-2xl rounded-t-lg border border-gray-200 p-4 max-h-96 overflow-y-auto">
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-bold text-lg">🛒 Cart</h2>
            <span class="bg-blue-600 text-white px-2 py-1 rounded-full text-sm">
                {{ count($cart) }}
            </span>
        </div>

        <div id="cartItems" class="space-y-2">
            @if (empty($cart))
                <p class="text-gray-500 text-sm">Don't have any product in the cart.</p>
            @else
                @php $total = 0; @endphp
                @foreach ($cart as $id => $item)
                    @php $total += $item['price'] * $item['quantity']; @endphp
                    <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                        <span class="text-sm">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                        <form action="{{ route('employee.removeFromCart', $id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">✕</button>
                        </form>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="mt-3 pt-3 border-t border-gray-200 font-bold">
            Total: RM {{ number_format($total ?? 0, 2) }}
        </div>

        {{-- BUTTON PAYMENT --}}
        @if (!empty($cart))
            <form action="{{ route('employee.processPayment') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit"
                    class="w-full px-4 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition text-lg"
                    onclick="return confirm('Confirm payment? Stock will be decrease.')">
                    PAYMENT
                </button>
            </form>
        @endif

        <a href="{{ route('employee.cart') }}"
            class="mt-2 w-full inline-block text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            Lihat Cart & Checkout
        </a>
    </div>

    {{-- JAVASCRIPT YANG BETUL --}}
    <script>
        function updateQuantity(productId, change) {
            const qtyInput = document.getElementById('qty-' + productId);
            const display = document.getElementById('display-qty-' + productId);
            let current = parseInt(qtyInput.value) || 1;
            let newQty = current + change;

            if (newQty < 1) newQty = 1;

            // UPDATE kedua-dua hidden input dan display
            qtyInput.value = newQty;
            display.textContent = newQty;
        }
    </script>

@endsection
