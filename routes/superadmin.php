<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\SuperAdminTenantController;
use App\Http\Controllers\SuperAdmin\SuperAdminPaymentController;
use App\Http\Controllers\SuperAdmin\SuperAdminSmsController;

Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('/users', SuperAdminUserController::class);
        Route::resource('/tenants', SuperAdminTenantController::class);
        Route::resource('/payments', SuperAdminPaymentController::class);
        Route::resource('/sms', SuperAdminSmsController::class);
    });
