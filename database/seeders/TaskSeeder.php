<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        foreach ($users as $user) {
            foreach ($categories as $category) {

                // Todo tasks
                Task::factory(rand(2, 5))
                    ->todo()
                    ->state([
                        'user_id' => $user->user_id,
                        'category_id' => $category->category_id,
                    ])
                    ->create();

                // In progress tasks
                Task::factory(rand(1, 3))
                    ->inProgress()
                    ->state([
                        'user_id' => $user->user_id,
                        'category_id' => $category->category_id,
                    ])
                    ->create();

                // Completed tasks
                Task::factory(rand(3, 7))
                    ->completed()
                    ->state([
                        'user_id' => $user->user_id,
                        'category_id' => $category->category_id,
                    ])
                    ->create();

                // Some overdue tasks
                if (rand(1, 3) === 1) {
                    Task::factory(rand(1, 2))
                        ->overdue()
                        ->state([
                            'user_id' => $user->user_id,
                            'category_id' => $category->category_id,
                        ])
                        ->create();
                }

                // High priority tasks
                if (rand(1, 2) === 1) {
                    Task::factory()
                        ->highPriority()
                        ->state([
                            'user_id' => $user->user_id,
                            'category_id' => $category->category_id,
                        ])
                        ->create();
                }
            }
        }
    }
}
