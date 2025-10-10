<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\RenterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\EmailController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Apartments
    Route::get('/apartments', [ApartmentController::class, 'index'])->name('apartments.index');
    Route::get('/apartments/create', [ApartmentController::class, 'create'])->name('apartments.create');
    Route::post('/apartments', [ApartmentController::class, 'store'])->name('apartments.store');
    Route::get('/apartments/{apartment}', [ApartmentController::class, 'show'])->name('apartments.show');
    Route::get('/apartments/{apartment}/edit', [ApartmentController::class, 'edit'])->name('apartments.edit');
    Route::put('/apartments/{apartment}', [ApartmentController::class, 'update'])->name('apartments.update');
    Route::delete('/apartments/{apartment}', [ApartmentController::class, 'destroy'])->name('apartments.destroy');

    // Renters
    Route::get('/renters', [RenterController::class, 'index'])->name('renters.index');
    Route::get('/renters/create', [RenterController::class, 'create'])->name('renters.create');
    Route::post('/renters', [RenterController::class, 'store'])->name('renters.store');
    Route::get('/renters/{renter}', [RenterController::class, 'show'])->name('renters.show');
    Route::get('/renters/{renter}/edit', [RenterController::class, 'edit'])->name('renters.edit');
    Route::put('/renters/{renter}', [RenterController::class, 'update'])->name('renters.update');
    Route::delete('/renters/{renter}', [RenterController::class, 'destroy'])->name('renters.destroy');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // SMS
    Route::get('/sms', [SmsController::class, 'index'])->name('sms.index');
    Route::get('/sms/create', [SmsController::class, 'create'])->name('sms.create');
    Route::post('/sms', [SmsController::class, 'store'])->name('sms.store');
    Route::delete('/sms/{smsLog}', [SmsController::class, 'destroy'])->name('sms.destroy');
    // Emails
    Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');
    Route::get('/emails/create', [EmailController::class, 'create'])->name('emails.create');
    Route::post('/emails', [EmailController::class, 'store'])->name('emails.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
