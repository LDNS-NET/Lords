<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Inertia\Inertia;

class SuperAdminSMSController extends Controller
{
    public function index()
    {
        $sms = SmsLog::with('user')->paginate(20);
        return Inertia::render('SuperAdmin/SMS/Index', compact('sms'));
    }
}

