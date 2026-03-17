<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Customer\PortalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// ── Public ──
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin', 'cashier' => redirect('/dashboard'),
            'customer' => redirect('/my/dashboard'),
            default => redirect('/login'),
        };
    }
    return redirect('/login');
});
Route::get('/pricelist', [ServiceController::class, 'pricelist'])->name('pricelist');

// ── Auth Required ──
Route::middleware('auth')->group(function () {

    // ── Profile (Breeze) ──
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Dashboard ──
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,cashier')
        ->name('dashboard');

    // ═══════════════════════════════════════
    // ADMIN & CASHIER ROUTES
    // ═══════════════════════════════════════
    Route::middleware('role:admin,cashier')->group(function () {

        // Transactions
        Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
        Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
        Route::get('/transactions/{transaction}/print', [TransactionController::class, 'printReceipt'])->name('transactions.print');

        // API search
        Route::get('/api/search-customer', [TransactionController::class, 'searchCustomer'])->name('api.search-customer');
    });

    // ═══════════════════════════════════════
    // ADMIN ONLY ROUTES
    // ═══════════════════════════════════════
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Service Categories
        Route::resource('service-categories', ServiceCategoryController::class)->except('show');

        // Services (Pricelist)
        Route::resource('services', ServiceController::class)->except('show');

        // Customers
        Route::resource('customers', CustomerController::class);
        Route::post('/customers/{customer}/vehicles', [CustomerController::class, 'addVehicle'])->name('customers.add-vehicle');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // ═══════════════════════════════════════
    // REPORTS (Admin only)
    // ═══════════════════════════════════════
    Route::middleware('role:admin')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/top-customers', [ReportController::class, 'topCustomers'])->name('top-customers');
    });

    // ═══════════════════════════════════════
    // CUSTOMER PORTAL
    // ═══════════════════════════════════════
    Route::middleware('role:customer')->prefix('my')->name('customer.')->group(function () {
        Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [PortalController::class, 'transactions'])->name('transactions');
        Route::get('/points', [PortalController::class, 'pointHistory'])->name('points');
        Route::get('/rewards', [PortalController::class, 'rewards'])->name('rewards');
        Route::post('/rewards/claim', [PortalController::class, 'claimReward'])->name('rewards.claim');
    });
});

require __DIR__.'/auth.php';