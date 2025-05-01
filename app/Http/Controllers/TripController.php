<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TripController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $trips = auth()->user()->trips()->latest()->get();
        $order = $request->get('order', 'desc'); // Default to 'desc' (latest to oldest)
        $trips = Trip::orderBy('start_date', $order)->get();
        return view('trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('trips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'total_budget' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $trip = auth()->user()->trips()->create($validated);

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Trip created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip): View
    {
        $this->authorize('view', $trip);
        return view('trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip): View
    {
        $this->authorize('update', $trip);
        return view('trips.edit', compact('trip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'total_budget' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $trip->update($validated);

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Trip updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorize('delete', $trip);

        $trip->delete();

        return redirect()->route('trips.index')
            ->with('success', 'Trip deleted successfully.');
    }

    /**
     * Display the budget overview for the specified trip.
     */
    public function budgetOverview(Trip $trip): View
    {
        $this->authorize('view', $trip);

        // Fetch expenses grouped by category
        $categories = $trip->expenses()
            ->selectRaw('category_id, SUM(amount) as total_expenses')
            ->with('category') // Ensure the category relationship is loaded
            ->groupBy('category_id')
            ->get();

        return view('trips.budget-overview', compact('trip', 'categories'));
    }
}