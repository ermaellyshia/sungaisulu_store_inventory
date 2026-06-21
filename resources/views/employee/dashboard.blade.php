@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-2 text-purple-700 dark:text-purple-300">Employee Dashboard</h1>
    <p class="text-gray-600 mb-6">Ready to serve customers</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-500 text-sm font-medium">Start New Sale</h3>
            <a href="{{ route('employee.cart') }}" class="mt-2 inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700">
                Create a new transaction
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-500 text-sm font-medium">Today's Sales</h3>
            <p class="text-2xl font-bold text-green-600">RM {{ number_format($todaySales ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500">{{ $todayTransactions ?? 0 }} transactions</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-500 text-sm font-medium">Items Sold Today</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $todayItems ?? 0 }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-500 text-sm font-medium">Quick Actions</h3>
            <a href="{{ route('employee.history') }}" class="text-purple-600 hover:underline">View History →</a>
        </div>
    </div>
</div>
@endsection