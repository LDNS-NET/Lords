<?php

namespace App\Http\Controllers;

use App\Models\Renter;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RenterController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Renter::with('apartment');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        $perPage = request('per_page', 10);
        $renters = $query->paginate($perPage);

        return Inertia::render('Renters/Index', [
            'renters' => $renters,
            'filters' => $request->only('search'),
            'perPage' => (int) $perPage,
        ]);
    }

    public function create()
    {
        $apartments = Apartment::all();

        return Inertia::render('Renters/Create', [
            'apartments' => $apartments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:renters,email',
            'phone_number' => 'required|string|max:20',
            'id_number' => 'required|string|unique:renters,id_number',
            'apartment_id' => 'required|exists:apartments,id',
            'house_number' => 'required|string|max:50',
            'move_in_date' => 'required|date',
        ]);

        Renter::create($validated);

        return redirect()->route('renters.index')
            ->with('success', 'Renter created successfully.');
    }

    public function show(Renter $renter)
    {
        $renter->load(['apartment', 'payments']);

        return Inertia::render('Renters/Show', [
            'renter' => $renter,
        ]);
    }

    public function edit(Renter $renter)
    {
        $apartments = Apartment::all();

        return Inertia::render('Renters/Edit', [
            'renter' => $renter,
            'apartments' => $apartments,
        ]);
    }

    public function update(Request $request, Renter $renter)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:renters,email,' . $renter->id,
            'phone_number' => 'required|string|max:20',
            'id_number' => 'required|string|unique:renters,id_number,' . $renter->id,
            'apartment_id' => 'required|exists:apartments,id',
            'house_number' => 'required|string|max:50',
            'move_in_date' => 'required|date',
        ]);

        $renter->update($validated);

        return redirect()->route('renters.index')
            ->with('success', 'Renter updated successfully.');
    }

    public function destroy(Renter $renter)
    {
        $renter->delete();

        return redirect()->route('renters.index')
            ->with('success', 'Renter deleted successfully.');
    }
}
