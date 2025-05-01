<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Accommodation',
                'color' => '#FF6B6B',
                'description' => 'Hotel, hostel, or other lodging expenses',
            ],
            [
                'name' => 'Transportation',
                'color' => '#4ECDC4',
                'description' => 'Flights, trains, buses, taxis, and other transport',
            ],
            [
                'name' => 'Food & Drinks',
                'color' => '#45B7D1',
                'description' => 'Restaurants, groceries, and beverages',
            ],
            [
                'name' => 'Activities',
                'color' => '#96CEB4',
                'description' => 'Tours, attractions, and entertainment',
            ],
            [
                'name' => 'Shopping',
                'color' => '#FFEEAD',
                'description' => 'Souvenirs and other purchases',
            ],
            [
                'name' => 'Insurance',
                'color' => '#D4A5A5',
                'description' => 'Travel insurance and medical coverage',
            ],
            [
                'name' => 'Miscellaneous',
                'color' => '#9B9B9B',
                'description' => 'Other expenses not covered by other categories',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 