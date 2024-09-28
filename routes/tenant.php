<?php

declare(strict_types=1);

use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\QzController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CashCutController;
use App\Http\Controllers\BarcodeController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::redirect('/', '/login');

    Route::middleware(['auth', 'verified', 'cash-register'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('home')->middleware('redirect-by-role');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/sales/{id}/ticket', [SaleController::class, 'generateTicket'])->name('sales.ticket');
        Route::get('/sales/{id}/ticket-download', [SaleController::class, 'ticketDownload'])->name('sales.ticket-download');
        Route::post('/cash-registers/select', [CashRegisterController::class, 'select'])->name('cash-registers.select');
        Route::get('/barcodes', [BarcodeController::class, 'index'])->name('barcodes.index');
        Route::get('/barcodes/{productId}/{quantity}', [BarcodeController::class, 'show'])->name('barcodes.show');
        Route::get('/qz/sign-message', [QzController::class, 'index'])->name('qz.sign-message');
        Route::get('/sales/export/', [SaleController::class, 'export'])->name('sales.export');

        Route::resource('users', UserController::class);
        Route::resource('settings', SettingController::class);
        Route::resource('products', ProductController::class);
        Route::resource('sales', SaleController::class);
        Route::resource('purchases', PurchaseController::class);
        Route::resource('expenses', ExpenseController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('cash-flows', CashFlowController::class);
        Route::resource('cash-cuts', CashCutController::class);
        Route::resource('inventory-transactions', InventoryTransactionController::class);
        Route::resource('locations', LocationController::class);
        Route::resource('cash-registers', CashRegisterController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('quotations', QuotationController::class);
        Route::resource('currencies', CurrencyController::class)->only(['index']);
    });

    Route::get('/offline', function () {
        return view('vendor.laravelpwa.offline');
    });

    require __DIR__ . '/auth.php';
});
