<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

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
    
    Route::middleware(['auth', 'verified'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
            ->name('dashboard');

        // Apartments
        Route::resource('apartments', \App\Http\Controllers\ApartmentController::class);

        // Renters
        Route::resource('renters', \App\Http\Controllers\RenterController::class);

        // SMS
        Route::get('sms', [\App\Http\Controllers\SmsController::class, 'index'])
            ->name('sms.index');
        Route::get('sms/create', [\App\Http\Controllers\SmsController::class, 'create'])
            ->name('sms.create');
        Route::post('sms', [\App\Http\Controllers\SmsController::class, 'store'])
            ->name('sms.store');

        // Emails
        Route::get('emails', [\App\Http\Controllers\EmailController::class, 'index'])
            ->name('emails.index');
        Route::get('emails/create', [\App\Http\Controllers\EmailController::class, 'create'])
            ->name('emails.create');
        Route::post('emails', [\App\Http\Controllers\EmailController::class, 'store'])
            ->name('emails.store');

        // Payments
        Route::resource('payments', \App\Http\Controllers\PaymentController::class);
        Route::post('payments/initiate', [\App\Http\Controllers\PaymentController::class, 'initiate'])
            ->name('payments.initiate');
        Route::get('payments/verify', [\App\Http\Controllers\PaymentController::class, 'verify'])
            ->name('payments.verify');
        Route::get('payments/export', [\App\Http\Controllers\PaymentController::class, 'export'])
            ->name('payments.export');
    });

    // Paystack webhook (outside auth middleware)
    Route::post('payments/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])
        ->name('payments.webhook');

    require __DIR__.'/auth.php';
});
