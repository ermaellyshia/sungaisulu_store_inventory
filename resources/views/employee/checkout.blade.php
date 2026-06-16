@extends('layouts.app')

@section('title', 'Checkout')
@section('header', 'Checkout')
@section('subheader', 'Complete the transaction')

@section('content')
<div class="bg-purple-50 dark:bg-gray-800 rounded-2xl shadow-lg p-6">
    <div class="text-center py-12">
        <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-2">Checkout</h3>
        <p class="text-gray-500 dark:text-gray-500">Add items to cart first</p>
    </div>
</div>
@endsection