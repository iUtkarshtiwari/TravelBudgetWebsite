<!-- filepath: e:\laraval\travel-budget-tracker\travel-budget-tracker\resources\views\expenses\show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expense Details') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900">{{ $expense->description }}</h3>
            <p class="text-sm text-gray-500">Category: {{ $expense->category->name ?? 'Uncategorized' }}</p>
            <p class="text-sm text-gray-500">Amount: ${{ number_format($expense->amount, 2) }}</p>
            <p class="text-sm text-gray-500">Date: {{ $expense->date->format('M d, Y') }}</p>
            <p class="text-sm text-gray-500">Currency: {{ $expense->currency }}</p>
            <p class="text-sm text-gray-500">Notes: {{ $expense->notes }}</p>
        </div>
    </div>
</x-app-layout>