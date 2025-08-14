<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Categories
        $defaultCategories = [
            [
                'name' => 'Work',
                'color' => '#3b82f6',
                'description' => 'Work-related tasks and projects',
                'is_default' => true,
            ],
            [
                'name' => 'Personal',
                'color' => '#10b981',
                'description' => 'Personal tasks and activities',
                'is_default' => true,
            ],
            [
                'name' => 'Shopping',
                'color' => '#f59e0b',
                'description' => 'Shopping lists and purchases',
                'is_default' => true,
            ],
            [
                'name' => 'Health',
                'color' => '#ef4444',
                'description' => 'Health and fitness related tasks',
                'is_default' => false,
            ],
            [
                'name' => 'Education',
                'color' => '#8b5cf6',
                'description' => 'Learning and educational activities',
                'is_default' => false,
            ],
        ];

        foreach ($defaultCategories as $category) {
            Category::firstOrCreate(
                $category
            );
        }

        // Get existing category names to avoid duplicates
        $existingNames = Category::pluck('name')->toArray();
        
        // Additional unique categories
        $additionalCategories = [
            'Finance', 'Travel', 'Home', 'Social', 'Hobbies',
            'Family', 'Projects', 'Goals', 'Meetings', 'Reading'
        ];
        
        $uniqueCategories = array_diff($additionalCategories, $existingNames);
    }
}
