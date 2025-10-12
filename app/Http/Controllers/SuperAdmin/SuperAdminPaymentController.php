<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Inertia\Inertia;

class SuperAdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payments::with('user')->paginate(20);
        return Inertia::render('SuperAdmin/Payments/Index', compact('payments'));
    }
}
