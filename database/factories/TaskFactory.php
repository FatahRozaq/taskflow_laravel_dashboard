<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dueDate = fake()->optional(0.7)->dateTimeBetween('now', '+2 months');
        
        return [
            'user_id' => null,
            'category_id' => null,
            'title' => fake('en_US')->sentence(4),
            'description' => fake('en_US')->paragraph(),
            'status' => fake()->randomElement(['Todo', 'In Progress', 'Done']),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High']),
            'due_date' => $dueDate,
            'completed_at' => function (array $attributes) {
                return $attributes['status'] === 'Done' 
                    ? fake()->dateTimeBetween('-1 month', 'now') 
                    : null;
            },
        ];
    }

    public function todo(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Todo',
            'completed_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'In Progress',
            'completed_at' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Done',
            'completed_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'High',
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => fake()->randomElement(['Todo', 'In Progress']),
            'due_date' => fake()->dateTimeBetween('-1 week', '-1 day'),
            'completed_at' => null,
        ]);
    }
}
