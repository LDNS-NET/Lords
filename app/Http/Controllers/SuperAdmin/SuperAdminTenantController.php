<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuperAdminTenantController extends Controller
{
    public function index()
    {
        return Inertia::render('SuperAdmin/Tenants/Index');
    }
}
