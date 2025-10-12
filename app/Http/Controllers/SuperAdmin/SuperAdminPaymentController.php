<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Inertia\Inertia;

class SuperAdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('renter')->get();

        return Inertia::render('SuperAdmin/Payments/Index', [
            'payments' => $payments,
        ]);
    }
}
