<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReportController;
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

        // Reports
        Route::get('/reports/sales-daily', [ReportController::class, 'salesDaily'])->name('reports.sales-daily');
        Route::get('/reports/sales-daily/csv', [ReportController::class, 'exportSalesDailyCsv'])->name('reports.sales-daily.csv');
        Route::get('/reports/sales-monthly', [ReportController::class, 'salesMonthly'])->name('reports.sales-monthly');
        Route::get('/reports/sales-monthly/csv', [ReportController::class, 'exportSalesMonthlyCsv'])->name('reports.sales-monthly.csv');
        Route::get('/reports/item-utilization', [ReportController::class, 'itemUtilization'])->name('reports.item-utilization');
        Route::get('/reports/item-utilization/csv', [ReportController::class, 'exportItemUtilizationCsv'])->name('reports.item-utilization.csv');
        Route::get('/reports/overdue', [ReportController::class, 'overdueRentals'])->name('reports.overdue');
        Route::get('/reports/overdue/csv', [ReportController::class, 'exportOverdueCsv'])->name('reports.overdue.csv');
    });

    // Admin + Kasir routes
    Route::middleware('role:admin,kasir')->group(function () {
        Route::resource('customers', CustomerController::class);

        // POS
        Route::get('/pos', [PosController::class, 'create'])->name('pos.create');
        Route::get('/pos/search-items', [PosController::class, 'searchItems'])->name('pos.search-items');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/{rental}/receipt', [PosController::class, 'receipt'])->name('pos.receipt');

        // Rentals
        Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
        Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
        Route::get('/rentals/{rental}/return', [RentalController::class, 'returnForm'])->name('rentals.return');
        Route::post('/rentals/{rental}/return', [RentalController::class, 'processReturn'])->name('rentals.process-return');
        Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');

        // Payments
        Route::post('/rentals/{rental}/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });
});
