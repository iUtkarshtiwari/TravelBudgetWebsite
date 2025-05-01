<!-- filepath: e:\laraval\travel-budget-tracker\travel-budget-tracker\resources\views\expenses\category-breakdown.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Breakdown') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Expense Breakdown by Category</h3>
            @if($categories->isEmpty())
                <p class="text-sm text-gray-500">No expenses recorded for this trip.</p>
            @endif
            <ul class="space-y-4">
                @foreach ($categories as $category)
                    <li class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">
                            {{ $category->category->name ?? 'Category ID: ' . $category->category_id }}
                        </span>
                        <span class="text-sm text-gray-500">
                            ${{ number_format($category->total_expenses, 2) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>