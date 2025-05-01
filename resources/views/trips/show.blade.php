<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $trip->name }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('trips.expenses.create', $trip) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Add Expense') }}
                </a>
                <a href="{{ route('trips.budgets.create', $trip) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Add Budget') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Trip Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Trip Details</h3>
                            <dl class="mt-2 space-y-1">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Destination</dt>
                                    <dd class="text-sm text-gray-900">{{ $trip->destination }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Dates</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $trip->start_date->format('M d, Y') }} -
                                        {{ $trip->end_date->format('M d, Y') }}
                                    </dd>
                                </div>
                                @if($trip->description)
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="text-sm text-gray-900">{{ $trip->description }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Budget Summary</h3>
                            <dl class="mt-2 space-y-1">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Total Budget</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($trip->total_budget, 2) }}</dd>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-sm font-medium text-gray-900">Spent</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">${{ number_format($trip->total_expenses, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-sm font-medium text-gray-900">Remaining</span>
                                    <span
                                        class="text-sm font-medium {{ $trip->remaining_budget < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        ${{ number_format($trip->remaining_budget, 2) }}
                                    </span>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                            <div class="mt-2 space-y-2">
                                <a href="{{ route('trips.budget-overview', $trip) }}"
                                    class="block w-full text-center bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    View Budget Overview
                                </a>
                                <a href="{{ route('trips.expenses.category-breakdown', $trip) }}"
                                    class="block w-full text-center bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                    View Category Breakdown
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses and Budgets -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Expenses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Expenses</h3>
                        @if($trip->expenses->count() > 0)
                            <div class="space-y-4">
                                @foreach($trip->expenses->take(5) as $expense)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $expense->description }}</p>
                                            <p class="text-xs text-gray-500">{{ $expense->category->name }} •
                                                {{ $expense->date->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                ${{ number_format($expense->amount, 2) }}</p>
                                            <p class="text-xs text-gray-500">{{ $expense->currency }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                @if($trip->expenses->count() > 5)
                                    <div class="text-center">
                                        <a href="{{ route('trips.expenses.index', $trip) }}"
                                            class="text-sm text-blue-500 hover:text-blue-700">
                                            View All Expenses
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No expenses recorded yet.</p>
                        @endif
                    </div>
                </div>



                <!-- Budget Categories -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Budget Categories</h3>

                        @if($trip->budgets->count() > 0)
                                                <div class="space-y-4">
                                                    @php
                                                        // Group budgets by category_id
                                                        $groupedBudgets = $trip->budgets->groupBy('category_id');
                                                    @endphp

                                                    @foreach($groupedBudgets as $categoryId => $budgets)
                                                                            @php
                                                                                $categoryName = optional($budgets->first()->category)->name ?? 'Unknown Category';
                                                                                $totalBudget = $budgets->sum('amount');
                                                                                $totalSpent = $trip->expenses->where('category_id', $categoryId)->sum('amount');
                                                                                $remaining = $totalBudget - $totalSpent;
                                                                                $percentage = $totalBudget > 0 ? min(round(($totalSpent / $totalBudget) * 100, 2), 100) : 0;
                                                                                $color = $percentage >= 80 ? 'bg-red-600' : ($percentage >= 50 ? 'bg-yellow-500' : 'bg-green-500');
                                                                            @endphp

                                                                            <div>
                                                                                <!-- Top Line: Category name and budget -->
                                                                                <div class="flex justify-between items-center mb-1">
                                                                                    <span class="text-sm font-medium text-gray-900">{{ $categoryName }}</span>
                                                                                    <span class="text-sm text-gray-500">${{ number_format($totalBudget, 2) }}</span>
                                                                                </div>

                                                                                <!-- Progress Bar -->
                                                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                                                    <div class="{{ $color }} h-2.5 rounded-full transition-all duration-500"
                                                                                        style="width: {{ $percentage }}%;">
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Bottom Line: Spent and Remaining -->
                                                                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                                                                    <span>Spent: ${{ number_format($totalSpent, 2) }}</span>
                                                                                    <span>Remaining: ${{ number_format($remaining, 2) }}</span>
                                                                                </div>

                                                                                <!-- Edit Budget Button -->
                                                                                <div class="mt-2 text-right">
                                                                                    <a href="{{ route('trips.budgets.edit', ['trip' => $trip->id, 'budget' => $budgets->first()->id]) }}"
                                                                                        class="text-sm text-blue-500 hover:text-blue-700">
                                                                                        Edit Budget
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                    @endforeach
                                                </div>
                        @else
                            <p class="text-sm text-gray-500">No budget categories set up yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>