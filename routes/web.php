<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('trips.index');
    }
    return redirect()->route('register');
});

Route::get('/dashboard', function () {
    return redirect()->route('dashboard');
})->middleware(['auth']);

Route::get('/dashboard', function () {
    return redirect()->route('trips.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trip Routes
    Route::resource('trips', TripController::class);
    Route::get('trips/{trip}/budget-overview', [TripController::class, 'budgetOverview'])
        ->name('trips.budget-overview');

    // Expense Routes
    Route::prefix('trips/{trip}')->group(function () {
        Route::get('expenses/category-breakdown', [ExpenseController::class, 'categoryBreakdown'])
            ->name('trips.expenses.category-breakdown');

        Route::resource('expenses', ExpenseController::class)
            ->except('show')
            ->names([
                'index' => 'trips.expenses.index',
                'create' => 'trips.expenses.create',
                'store' => 'trips.expenses.store',
                'edit' => 'trips.expenses.edit',
                'update' => 'trips.expenses.update',
                'destroy' => 'trips.expenses.destroy',
            ]);

        Route::get('expenses/{expense}', [ExpenseController::class, 'show'])
            ->name('trips.expenses.show');
    });



    // Budget Routes
    Route::prefix('trips/{trip}')->group(function () {
        Route::resource('budgets', BudgetController::class)
        ->names([
            'index' => 'trips.budgets.index',
            'create' => 'trips.budgets.create',
            'store' => 'trips.budgets.store',
            'edit' => 'trips.budgets.edit',
            'update' => 'trips.budgets.update',
            'destroy' => 'trips.budgets.destroy',
        ]);

        Route::get('budgets/allocate-remaining', [BudgetController::class, 'allocateRemaining'])
            ->name('trips.budgets.allocate-remaining');
        Route::post('budgets/update-allocation', [BudgetController::class, 'updateAllocation'])
            ->name('trips.budgets.update-allocation');
    });

    // Category Routes
    Route::resource('categories', CategoryController::class);
});

require __DIR__ . '/auth.php';
