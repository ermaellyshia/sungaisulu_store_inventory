@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-2 text-purple-700 dark:text-purple-300">📋 Transaction's History</h1>

    @if($transactions->isEmpty())
        <div class="bg-yellow-100 p-6 rounded-lg text-center">
            <p class="text-yellow-700">No Transaction Found.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Transaction Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4">#{{ $transaction->id }}</td>
                        <td class="px-6 py-4">{{ $transaction->transaction_number }}</td>
                        <td class="px-6 py-4">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">{{ $transaction->customer_name }}</td>
                        <td class="px-6 py-4">RM {{ number_format($transaction->total, 2) }}</td>
                        <td class="px-6 py-4">
                            @foreach($transaction->items as $item)
                                <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs m-1">
                                    {{ $item->product->product_name }} (×{{ $item->quantity }})
                                </span>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $transactions->links() }}</div>
    @endif

    <a href="{{ route('employee.dashboard') }}" class="mt-6 inline-block px-6 py-2 bg-purple-600 text-white rounded-lg">
        ← Back
    </a>
</div>
@endsection