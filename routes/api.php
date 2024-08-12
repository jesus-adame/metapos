<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\InventoryTransactionController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CashRegisterController;
use App\Http\Controllers\Api\CashFlowController;
use App\Http\Controllers\Api\CashCutController;
use App\Http\Controllers\Api\BranchController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', InitializeTenancyByDomain::class])->name('api.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('cash-flows', CashFlowController::class);
    Route::resource('cash-cuts', CashCutController::class);
    Route::resource('inventory-transactions', InventoryTransactionController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('cash-registers', CashRegisterController::class);

    Route::post('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::post('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::post('/suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search');
    Route::post('/cash-registers/search', [CashRegisterController::class, 'search'])->name('sales.search');

    Route::post('/cash-registers/select', [CashRegisterController::class, 'select'])->name('cash-registers.select');
});
