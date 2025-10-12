<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Inertia\Inertia;

class SuperAdminSMSController extends Controller
{
    public function index()
    {
        $smsLogs = SmsLog::all();

        return Inertia::render('SuperAdmin/SMS/Index', [
            'smsLogs' => $smsLogs,
        ]);
    }
}

