<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Apartment;
use App\Models\Payment;
use App\Models\SmsLog;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalApartments = Apartment::count();
        $totalPayments = Payment::sum('amount');
        $totalSms = SmsLog::count();

        $recentUsers = User::latest()->take(10)->get(['email', 'name', 'created_at']);
        $recentSms = SmsLog::latest()->take(10)->get(['id', 'recipient_name', 'created_at']);

        $recentActivity = collect()
            ->merge($recentUsers->map(fn($u) => [
                'type' => 'user',
                'message' => "New user <b>{$u->name}</b> registered",
                'time' => $u->created_at->diffForHumans(),
                'created_at' => $u->created_at,
            ]))
            ->merge($recentSms->map(fn($s) => [
                'type' => 'sms',
                'message' => "SMS sent to <b>{$s->recipient_name}</b>",
                'time' => $s->created_at->diffForHumans(),
                'created_at' => $s->created_at,
            ]))
            ->sortByDesc('created_at')
            ->take(10)
            ->values();


        return Inertia::render('SuperAdmin/Dashboard/Index', [
            'totalUsers' => $totalUsers,
            'totalApartments' => $totalApartments,
            'totalPayments' => $totalPayments,
            'totalSms' => $totalSms,
            'recentActivity' => $recentActivity,
        ]);
    }
}