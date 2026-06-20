@extends('layouts.app')

@section('title', 'Daily Report')
@section('header', '📊 Daily Report')
@section('subheader', 'View sales transactions by date')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
    {{-- Header with Date Picker and Download Buttons --}}
    <div class="flex flex-wrap justify-between items-center mb-6 gap-2">
        <form method="GET" class="flex gap-2">
            <input type="date" name="date" value="{{ $date ?? now()->toDateString() }}" 
                   class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                View
            </button>
        </form>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.download.pdf', ['date' => $date ?? now()->toDateString()]) }}" 
               class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l3-3m0 0l3 3m-3-3v6"/>
                </svg>
                PDF
            </a>
            <a href="{{ route('admin.reports.download.excel', ['date' => $date ?? now()->toDateString()]) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                </svg>
                Excel
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Sales</h3>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">RM {{ number_format($totalSales ?? 0, 2) }}</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Transactions</h3>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalTransactions ?? 0 }}</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Items Sold</h3>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $totalItems ?? 0 }}</p>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions ?? [] as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $transaction->transaction_number }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->created_at->format('H:i:s') }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->customer_name ?? 'Walk-in' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $transaction->payment_method == 'cash' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $transaction->payment_method == 'card' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $transaction->payment_method == 'ewallet' ? 'bg-purple-100 text-purple-800' : '' }}
                                dark:bg-opacity-20">
                                {{ ucfirst($transaction->payment_method ?? 'cash') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($transaction->items as $item)
                                    <span class="inline-block bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs text-gray-700 dark:text-gray-300">
                                        {{ $item->product->product_name }} × {{ $item->quantity }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">RM {{ number_format($transaction->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p>No transactions found for this date.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right font-bold text-gray-700 dark:text-gray-300">Grand Total:</td>
                        <td class="px-6 py-4 font-bold text-lg text-green-600 dark:text-green-400">RM {{ number_format($totalSales ?? 0, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Footer Info --}}
    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center">
        Generated on {{ now()->format('d/m/Y H:i:s') }} | Total Transactions: {{ $totalTransactions ?? 0 }}
    </div>
</div>
@endsection