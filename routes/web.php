<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Homepage
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/employee/dashboard');
    }
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| FORGOT PASSWORD ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function () {
    return back()->with('status', 'Reset link sent to your email!');
})->name('password.email');

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/api/products', [ProductController::class, 'getAllProducts']);
Route::get('/api/products/{id}', [ProductController::class, 'getProduct']);
Route::get('/api/categories', [CategoryController::class, 'getAllCategories']);
Route::post('/api/transactions', [TransactionController::class, 'store']);
Route::get('/api/reports', [TransactionController::class, 'getReports']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            $totalProducts = Product::count();
            $lowStockCount = Product::whereRaw('stock <= min_stock')->count();
            $totalEmployees = User::where('role', 'employee')->count();
            $totalStockValue = Product::sum(DB::raw('stock * price'));
            $lowStockProducts = Product::whereRaw('stock <= min_stock')->get();
            
            return view('admin.dashboard', compact(
                'totalProducts', 
                'lowStockCount', 
                'totalEmployees', 
                'totalStockValue', 
                'lowStockProducts'
            ));
        })->name('admin.dashboard');

        // Employees Management
        Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

        // Products Management
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
        Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
        
        // Categories Management
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Reports
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