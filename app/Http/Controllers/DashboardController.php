<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Renter;
use App\Models\Payment;
use App\Models\SmsLog;
use App\Models\EmailLog;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $recent_payments = $this->getRecentPayments();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recent_payments' => $recent_payments,
        ]);
    }

    // ✅ Add this
    public function data()
    {
        return response()->json([
            'stats' => $this->getStats(),
            'recent_payments' => $this->getRecentPayments(),
        ]);
    }

    private function getStats()
    {
        return [
            'total_apartments' => \App\Models\Apartment::count(),
            'total_renters' => \App\Models\Renter::count(),
            'pending_payments' => \App\Models\Payment::where('status', 'pending')->count(),
            'total_payments' => \App\Models\Payment::where('status', 'success')->sum('amount'),
            'sms_sent' => \App\Models\SmsLog::where('status', 'sent')->count(),
            'emails_sent' => \App\Models\EmailLog::where('status', 'sent')->count(),
        ];
    }

    private function getRecentPayments()
    {
        return Payment::with('renter')
            ->latest()
            ->take(10)
            ->get();
    }
}
