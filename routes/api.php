<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\SaleController;
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

Route::middleware(['auth:sanctum', InitializeTenancyByDomain::class])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/search', [ProductController::class, 'search'])->name('products.search');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/search', [UserController::class, 'search'])->name('users.search');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search');

    Route::post('/sales', [SaleController::class, 'store'])->name('sale.store');

    Route::get('/cash-registers', [CashRegisterController::class, 'index'])->name('sale.index');
    Route::get('/cash-cuts', [CashCutController::class, 'index'])->name('cash-cuts.index');
    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('/cash-flows', [CashFlowController::class, 'index'])->name('cash-flows.index');
    Route::get('/cash-flows', [CashFlowController::class, 'index'])->name('cash-flows.index');
    Route::resource('inventory-transactions', InventoryTransactionController::class);
});
