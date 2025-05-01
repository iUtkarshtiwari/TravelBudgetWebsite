<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budget Overview') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900">{{ $trip->name }}</h3>
                <p class="text-sm text-gray-500">{{ $trip->destination }}</p>

                <div class="mt-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">Total Budget</span>
                        <span
                            class="text-sm font-medium text-gray-900">${{ number_format($trip->total_budget, 2) }}</span>
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
                </div>

                <div class="mt-6">
                    <h4 class="text-md font-semibold text-gray-900">Expense Breakdown</h4>
                    @if($categories->isEmpty())
                        <p class="text-sm text-gray-500">No expenses recorded for this trip.</p>
                    @endif
                    <ul class="mt-4 space-y-2">
                        @foreach ($categories as $category)
                            <li class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">
                                    {{ $category->category->name ?? 'Uncategorized' }}
                                </span>
                                <span class="text-sm text-gray-900">
                                    ${{ number_format($category->total_expenses, 2) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>