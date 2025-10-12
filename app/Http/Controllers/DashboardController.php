<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Renter;
use App\Models\Payment;
use App\Models\SmsLog;
use App\Models\User;
use App\Models\EmailLog;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $recent_payments = $this->getRecentPayments();
        $user = Auth::user();
        
        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recent_payments' => $recent_payments,
            'subscription_expires_at' => $user?->subscription_expires_at, // Pass to Vue
        ]);
    }

    // Optional JSON endpoint for live updates
    public function data()
    {
        $user = Auth::user();

        return response()->json([
            'stats' => $this->getStats(),
            'recent_payments' => $this->getRecentPayments(),
            'subscription_expires_at' => $user?->subscription_expires_at,
        ]);
    }

    private function getStats()
    {
        return [
            'total_apartments' => Apartment::count(),
            'total_renters' => Renter::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_payments' => Payment::where('status', 'success')->sum('amount'),
            'sms_sent' => SmsLog::where('status', 'sent')->count(),
            'emails_sent' => EmailLog::where('status', 'sent')->count(),
        ];
    }

    private function getRecentPayments()
    {
        return Payment::with('renter')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'renter_name' => $payment->renter->name ?? 'N/A',
                    'amount' => $payment->amount,
                    'reference' => $payment->reference,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at,
                ];
            });
    }
}
