<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        $apartments = Apartment::withCount('renters')
            ->paginate($perPage);

        return Inertia::render('Apartments/Index', [
            'apartments' => $apartments,
            'perPage' => (int) $perPage,
        ]);
    }

    public function create()
    {
        return Inertia::render('Apartments/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'number_of_units' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Apartment::create($validated);

        return redirect()->route('apartments.index')
            ->with('success', 'Apartment created successfully.');
    }

    public function show(Apartment $apartment)
    {
        $apartment->load('renters');

        return Inertia::render('Apartments/Show', [
            'apartment' => $apartment,
        ]);
    }

    public function edit(Apartment $apartment)
    {
        return Inertia::render('Apartments/Edit', [
            'apartment' => $apartment,
        ]);
    }

    public function update(Request $request, Apartment $apartment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'number_of_units' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $apartment->update($validated);

        return redirect()->route('apartments.index')
            ->with('success', 'Apartment updated successfully.');
    }

    public function destroy(Apartment $apartment)
    {
        $apartment->delete();

        return redirect()->route('apartments.index')
            ->with('success', 'Apartment deleted successfully.');
    }
}
