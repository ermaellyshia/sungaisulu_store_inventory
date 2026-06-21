@extends('layouts.app')

@section('title', 'Monthly Report')
@section('header', '📊 Monthly Report')
@section('subheader', 'View sales transactions by month')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
        {{-- Header with Month Picker and Download Buttons --}}
        <div class="flex flex-wrap justify-between items-center mb-6 gap-2">
            <form method="GET" class="flex gap-2">
                <input type="month" name="month" value="{{ $month ?? now()->format('Y-m') }}"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <button type="submit"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition shadow-md font-semibold">
                    View
                </button>
            </form>

            <div class="flex gap-2">
                <a href="{{ route('admin.reports.download.pdf', ['month' => $month ?? now()->format('Y-m')]) }}"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-1">
                    📄 PDF
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <h3 class="text-gray-500 dark:text-gray-300 text-sm font-medium">Total Sales</h3>
                <p class="text-2xl font-bold text-green-600 dark:text-white">RM {{ number_format($totalSales ?? 0, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <h3 class="text-gray-500 dark:text-gray-300 text-sm font-medium">Transactions</h3>
                <p class="text-2xl font-bold text-blue-600 dark:text-white">{{ $totalTransactions ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <h3 class="text-gray-500 dark:text-gray-300 text-sm font-medium">Items Sold</h3>
                <p class="text-2xl font-bold text-purple-600 dark:text-white">{{ $totalItems ?? 0 }}</p>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Invoice</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Customer</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Payment</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Items</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions ?? [] as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    #{{ $transaction->transaction_number }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $transaction->customer_name ?? 'Walk-in' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full 
                                {{ $transaction->payment_method == 'cash' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : '' }}
                                {{ $transaction->payment_method == 'card' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                                {{ $transaction->payment_method == 'ewallet' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300' : '' }}">
                                        {{ ucfirst($transaction->payment_method ?? 'cash') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($transaction->items as $item)
                                            <span
                                                class="inline-block bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs text-gray-700 dark:text-gray-300">
                                                {{ $item->product->product_name }} × {{ $item->quantity }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">RM
                                    {{ number_format($transaction->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <p>No transactions found for this month.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right font-bold text-gray-700 dark:text-gray-300">Grand
                                Total:</td>
                            <td class="px-6 py-4 font-bold text-lg text-green-600 dark:text-green-400">RM
                                {{ number_format($totalSales ?? 0, 2) }}</td>
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
