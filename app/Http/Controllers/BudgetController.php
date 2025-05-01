<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BudgetController extends Controller
{
    /**
     * Display a listing of the budgets for a trip.
     */
    use AuthorizesRequests;
    public function index(Trip $trip): View
    {
        $this->authorize('view', $trip);

        $budgets = $trip->budgets()->with('category')->get();

        return view('budgets.index', compact('trip', 'budgets'));
    }

    /**
     * Show the form for creating a new budget.
     */
    public function create(Trip $trip): View
    {
        $this->authorize('update', $trip);

        $categories = Category::all();
        $existingCategoryIds = $trip->budgets()->pluck('category_id');
        $availableCategories = $categories->whereNotIn('id', $existingCategoryIds);

        return view('budgets.create', compact('trip', 'availableCategories'));
    }

    /**
     * Store a newly created budget in storage.
     */
    public function store(Request $request, Trip $trip): RedirectResponse
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id|unique:budgets,category_id,NULL,id,trip_id,' . $trip->id,
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string',
        ]);

        $trip->budgets()->create($validated);

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Budget added successfully.');
    }

    /**
     * Show the form for editing the specified budget.
     */
    public function edit(Trip $trip, Budget $budget): View
    {
        $this->authorize('update', $trip);

        $categories = Category::all();

        return view('budgets.edit', compact('trip', 'budget', 'categories'));
    }

    /**
     * Update the specified budget in storage.
     */
    public function update(Request $request, Trip $trip, Budget $budget): RedirectResponse
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string',
        ]);

        $budget->update($validated);

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Budget updated successfully.');
    }

    /**
     * Remove the specified budget from storage.
     */
    public function destroy(Trip $trip, Budget $budget): RedirectResponse
    {
        $this->authorize('update', $trip);

        $budget->delete();

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Budget deleted successfully.');
    }
}