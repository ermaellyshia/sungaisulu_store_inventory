@extends('layouts.app')

@section('title', 'Employee Dashboard')
@section('header', 'POS Dashboard')
@section('subheader', 'Ready to serve customers')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('employee.cart') }}" 
       class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition hover:-translate-y-1">
        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 1.5M17 13l1.5 1.5M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
        </svg>
        <h3 class="text-xl font-bold">Start New Sale</h3>
        <p class="text-indigo-200 mt-2">Create a new transaction</p>
    </a>
    
    <div class="bg-purple-50 dark:bg-gray-800 rounded-2xl shadow-lg p-8 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300">Today's Sales</h3>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">RM 0.00</p>
    </div>
</div>
@endsection