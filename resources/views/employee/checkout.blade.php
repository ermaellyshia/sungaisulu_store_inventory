@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">📋 Pengesahan Checkout</h1>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif

    @php
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    @endphp

    @if(empty($cart))
        <div class="bg-yellow-100 p-6 rounded-lg text-center">
            <p class="text-yellow-700">Cart kosong.</p>
            <a href="{{ route('employee.dashboard') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg">
                Kembali ke Dashboard
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
            
            <div class="space-y-3">
                @foreach($cart as $id => $item)
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <div>
                        <span class="font-medium">{{ $item['name'] }}</span>
                        <span class="text-gray-600 text-sm"> × {{ $item['quantity'] }}</span>
                    </div>
                    <span>RM {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t-2 border-gray-300">
                <div class="flex justify-between text-xl font-bold">
                    <span>Jumlah Keseluruhan:</span>
                    <span>RM {{ number_format($total, 2) }}</span>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-4">
                <form action="{{ route('employee.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 text-lg"
                            onclick="return confirm('Sahkan checkout? Stok akan dikurangkan.')">
                        ✅ Sahkan & Checkout
                    </button>
                </form>
                <a href="{{ route('employee.cart') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    ← Kembali ke Cart
                </a>
            </div>
        </div>
    @endif
</div>
@endsection