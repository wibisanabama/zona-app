<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }

    return redirect('/login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('items', ItemController::class);
    });

    // Admin + Kasir routes
    Route::middleware('role:admin,kasir')->group(function () {
        Route::resource('customers', CustomerController::class);

        // POS
        Route::get('/pos', [PosController::class, 'create'])->name('pos.create');
        Route::get('/pos/search-items', [PosController::class, 'searchItems'])->name('pos.search-items');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/{rental}/receipt', [PosController::class, 'receipt'])->name('pos.receipt');
    });
});
