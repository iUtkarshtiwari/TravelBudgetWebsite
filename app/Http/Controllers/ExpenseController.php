<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Trip;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class ExpenseController extends Controller
{
    use AuthorizesRequests;
    public function index(Trip $trip)
    {
        $this->authorize('view', $trip);

        $expenses = $trip->expenses()
            ->with('category')
            ->latest()
            ->get();

        return view('expenses.index', compact('trip', 'expenses'));
    }


    public function show(Trip $trip, Expense $expense)
    {
        $this->authorize('view', $trip);

        // Ensure the expense belongs to the trip
        if ($expense->trip_id !== $trip->id) {
            abort(404, 'Expense not found for this trip.');
        }

        return view('expenses.show', compact('trip', 'expense'));
    }


    public function create(Trip $trip)
    {
        $this->authorize('update', $trip);

        // Fetch all categories
        $categories = Category::all();

        // Pass categories and trip to the view
        return view('expenses.create', compact('trip', 'categories'));
    }

    public function store(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'currency' => ['required', 'string', 'size:3'],
            'notes' => ['nullable', 'string'],
        ]);

        // Create the expense
        $trip->expenses()->create($validated);

        // Redirect to the trip's show page
        return redirect()->route('trips.show', $trip)
            ->with('success', 'Expense added successfully.');
    }

    public function edit(Trip $trip, Expense $expense)
    {
        $this->authorize('update', $trip);

        $categories = Category::all();

        return view('expenses.edit', compact('trip', 'expense', 'categories'));
    }

    public function update(Request $request, Trip $trip, Expense $expense)
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('trips.expenses.index', $trip)
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Trip $trip, Expense $expense)
    {
        $this->authorize('update', $trip);

        $expense->delete();

        return redirect()->route('trips.expenses.index', $trip)
            ->with('success', 'Expense deleted successfully.');
    }

    public function categoryBreakdown(Trip $trip)
{
    $this->authorize('view', $trip);

    // Fetch expenses grouped by category
    $categories = $trip->expenses()
    ->selectRaw('category_id, SUM(amount) as total_expenses')
    ->join('categories', 'expenses.category_id', '=', 'categories.id')
    ->groupBy('category_id')
    ->get();

    return view('expenses.category-breakdown', compact('trip', 'categories'));

}
}