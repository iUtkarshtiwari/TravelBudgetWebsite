<!-- filepath: e:\laraval\travel-budget-tracker\travel-budget-tracker\resources\views\trips\index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Trips') }}
            </h2>
            <div class="flex items-center space-x-4">
                <!-- Sorting Dropdown -->
                <form method="GET" action="{{ route('trips.index') }}">
                    <select name="order" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Latest to Oldest</option>
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Oldest to Latest</option>
                    </select>
                </form>

                <a href="{{ route('trips.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('New Trip') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 px-4 sm:px-6 lg:px-8">
        @forelse ($trips as $trip)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $trip->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $trip->destination }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('trips.edit', $trip) }}" class="text-blue-500 hover:text-blue-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700"
                                    onclick="return confirm('Are you sure you want to delete this trip?')">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            {{ $trip->start_date->format('M d, Y') }} - {{ $trip->end_date->format('M d, Y') }}
                        </p>
                    </div>

                    <div class="mt-4">
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

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('trips.show', $trip) }}"
                            class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                            View Details
                        </a>
                        <a href="{{ route('trips.budget-overview', $trip) }}"
                            class="text-green-500 hover:text-green-700 text-sm font-medium">
                            Budget Overview
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No trips</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new trip.</p>
                    <div class="mt-6">
                        <a href="{{ route('trips.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            New Trip
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>