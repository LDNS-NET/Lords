<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Renter;
use App\Models\Payment;
use App\Models\SmsLog;
use App\Models\EmailLog;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_apartments' => Apartment::count(),
            'total_renters' => Renter::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_payments' => Payment::where('status', 'success')->sum('amount'),
            'sms_sent' => SmsLog::where('status', 'sent')->count(),
            'emails_sent' => EmailLog::where('status', 'sent')->count(),
        ];

        $recent_payments = Payment::with('renter')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recent_payments' => $recent_payments,
        ]);
    }
}
