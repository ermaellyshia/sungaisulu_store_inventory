@extends('layouts.app')

@section('title', 'POS Cart')
@section('header', 'Point of Sale')
@section('subheader', 'Scan products and process customer orders')

@section('content')
<div class="bg-purple-50 dark:bg-gray-800 rounded-2xl shadow-lg p-6">
    <div class="text-center py-12">
        <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 1.5M17 13l1.5 1.5M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-2">POS System</h3>
        <p class="text-gray-500 dark:text-gray-500">Click "Start New Sale" from dashboard</p>
    </div>
</div>
@endsection