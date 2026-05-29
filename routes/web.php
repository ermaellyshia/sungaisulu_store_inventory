<?php

use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| FAKE AUTH ROUTES (UNTUK HILANGKAN ERROR DULU)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return "Login page belum dibuat";
})->name('login');

Route::get('/register', function () {
    return "Register page belum dibuat";
})->name('register');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/employees', function () {
            return view('admin.employees.index');
        })->name('admin.employees');

        Route::get('/products', function () {
            return view('admin.products.index');
        })->name('admin.products');

        Route::get('/reports', function () {
            return view('admin.reports.index');
        })->name('admin.reports');
    });

    // Employee Routes
    Route::middleware('role:employee')->prefix('employee')->group(function () {

        Route::get('/dashboard', function () {
            return view('employee.dashboard');
        })->name('employee.dashboard');

        Route::get('/cart', function () {
            return view('employee.cart');
        })->name('employee.cart');

        Route::get('/checkout', function () {
            return view('employee.checkout');
        })->name('employee.checkout');
    });
});