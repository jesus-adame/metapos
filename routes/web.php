<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Central\TenantController;
use App\Http\Controllers\Central\SettingController;
use App\Http\Controllers\Central\ProfileController;
use App\Http\Controllers\Central\DashboardController;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->name('central.')->group(function () {
        Route::redirect('/', '/login');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            Route::resource('tenants', TenantController::class);
            Route::resource('settings', SettingController::class);
        });

        require __DIR__ . '/central/auth.php';
    });
}
