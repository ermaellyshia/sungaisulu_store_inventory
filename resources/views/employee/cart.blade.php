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
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
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

    {{-- Shopping Cart (Float) - DENGAN TOGGLE --}}
    <div id="cartPanel"
        class="fixed bottom-0 right-0 w-96 bg-white dark:bg-gray-800 shadow-2xl rounded-t-lg border border-gray-200 dark:border-gray-700 p-4 max-h-[80vh] overflow-y-auto transition-all duration-300">

        {{-- HEADER DENGAN TOGGLE --}}
        <div class="flex justify-between items-center mb-3 cursor-pointer" onclick="toggleCart()">
            <h2 class="font-bold text-lg text-gray-800 dark:text-white">🛒 Cart</h2>
            <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg id="toggleIcon" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        {{-- CART CONTENT --}}
        <div id="cartContent">
            <span class="bg-blue-600 text-white px-2 py-1 rounded-full text-sm inline-block mb-2">
                {{ count($cart) }}
            </span>

            <div id="cartItems" class="space-y-2">
                @if (empty($cart))
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Don't have any product in the cart.</p>
                @else
                    @php $total = 0; @endphp
                    @foreach ($cart as $id => $item)
                        @php $total += $item['price'] * $item['quantity']; @endphp
                        <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-2 rounded">
                            <span class="text-sm text-gray-800 dark:text-white">{{ $item['name'] }} ×
                                {{ $item['quantity'] }}</span>
                            <form action="{{ route('employee.removeFromCart', $id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">✕</button>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 font-bold text-gray-800 dark:text-white">
                Total: RM {{ number_format($total ?? 0, 2) }}
            </div>

            {{-- BUTTON PAYMENT - BUKA MODAL --}}
            @if (!empty($cart))
                <button onclick="openPaymentModal()"
                    class="w-full px-4 py-3 mt-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition text-lg">
                    PAYMENT
                </button>
            @endif
        </div>
    </div>

    {{-- PAYMENT MODAL --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closePaymentModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">💳 Payment Method</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @php
                $cart = session()->get('cart', []);
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            @endphp

            {{-- Order Summary --}}
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">RM {{ number_format($total, 2) }}</p>
            </div>

            <form action="{{ route('employee.processPayment') }}" method="POST" id="paymentForm">
                @csrf

                {{-- Payment Method --}}
                <div class="space-y-3 mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Payment
                        Method</label>

                    <div class="flex gap-3">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" checked class="hidden peer">
                            <div
                                class="border-2 border-gray-200 dark:border-gray-600 rounded-lg p-3 text-center peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition">
                                <span class="text-2xl">💵</span>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Cash</p>
                            </div>
                        </label>

                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="payment_method" value="card" class="hidden peer">
                            <div
                                class="border-2 border-gray-200 dark:border-gray-600 rounded-lg p-3 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition">
                                <span class="text-2xl">💳</span>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Card</p>
                            </div>
                        </label>

                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="payment_method" value="qrcode" class="hidden peer">
                            <div
                                class="border-2 border-gray-200 dark:border-gray-600 rounded-lg p-3 text-center peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 transition">
                                <span class="text-2xl">📱</span>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">QR Code</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Customer Name (Optional) --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer Name
                        (Optional)</label>
                    <input type="text" name="customer_name" value="Walk-in Customer"
                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-900"
                        placeholder="Walk-in Customer">
                </div>

                {{-- DEBUG - Hidden input to check --}}
                <input type="hidden" name="debug_payment" id="debug_payment" value="">

                <div class="flex gap-3">
                    <button type="button" onclick="closePaymentModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button type="submit" onclick="setPaymentMethod()"
                        class="flex-1 bg-green-600 text-white rounded-xl px-4 py-2.5 hover:bg-green-700 transition shadow-lg font-semibold">
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        function updateQuantity(productId, change) {
            const qtyInput = document.getElementById('qty-' + productId);
            const display = document.getElementById('display-qty-' + productId);
            let current = parseInt(qtyInput.value) || 1;
            let newQty = current + change;

            if (newQty < 1) newQty = 1;

            qtyInput.value = newQty;
            display.textContent = newQty;
        }

        function toggleCart() {
            const content = document.getElementById('cartContent');
            const icon = document.getElementById('toggleIcon');

            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(180deg)';
            }
        }

        function openPaymentModal() {
            document.getElementById('paymentModal').style.display = 'flex';
            // Reset debug
            document.getElementById('debug_payment').value = '';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        function setPaymentMethod() {
            const selected = document.querySelector('input[name="payment_method"]:checked');
            if (selected) {
                document.getElementById('debug_payment').value = selected.value;
                console.log('Payment Method Selected: ' + selected.value);
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePaymentModal();
            }
        });
    </script>

@endsection