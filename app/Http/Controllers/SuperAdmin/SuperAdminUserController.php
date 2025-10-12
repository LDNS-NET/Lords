<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Renter;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SuperAdminUserController extends Controller
{
    public function index()
    {
        $renters = Renter::with('user')->paginate(20); // optionally eager-load tenant user
        return Inertia::render('SuperAdmin/Users/Index', compact('renters'));
    }

    public function edit(Renter $renter)
    {
        return Inertia::render('SuperAdmin/Users/Edit', compact('renter'));
    }

    public function update(Request $request, Renter $renter)
    {
        $renter->update($request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'apartment_id' => 'nullable|exists:apartments,id',
        ]));

        return redirect()->route('superadmin.users.index')->with('success', 'Renter updated.');
    }

    public function destroy(Renter $renter)
    {
        $renter->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'Renter deleted.');
    }
}
