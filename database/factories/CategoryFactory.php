<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryNames = [
            'Finance', 'Travel', 'Home Improvement', 'Social Events', 'Hobbies',
            'Family Time', 'Side Projects', 'Long-term Goals', 'Client Meetings', 
            'Book Reading', 'Exercise', 'Meal Planning', 'Car Maintenance',
            'Gift Ideas', 'Vacation Planning', 'Skill Development', 'Networking',
            'Creative Projects', 'Volunteer Work', 'Investment Research'
        ];

        $name = fake()->unique()->randomElement($categoryNames);

        return [
            'name' => $name,
            'color' => fake()->randomElement([
                '#6366f1', '#ec4899', '#06b6d4', '#84cc16', 
                '#f97316', '#8b5cf6', '#ef4444', '#10b981',
                '#64748b', '#dc2626', '#7c3aed', '#059669'
            ]),
            'description' => "Tasks and activities related to {$name}",
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}
