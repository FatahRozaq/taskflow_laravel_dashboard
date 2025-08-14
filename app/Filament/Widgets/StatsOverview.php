<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Supaya widget muncul di dashboard default
    protected static bool $isLazy = false; 

    protected function getStats(): array
    {
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'Done')->count();

        $completionRate = $totalTasks > 0 
            ? round(($completedTasks / $totalTasks) * 100, 2) 
            : 0;

        return [
            Stat::make('Total Users', User::count())
                ->description('Number of registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Tasks', $totalTasks)
                ->description('Number of tasks in system')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Completion Rate', $completionRate . '%')
                ->description('Percentage of completed tasks')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($completionRate >= 75 ? 'success' : ($completionRate >= 50 ? 'warning' : 'danger')),
        ];
    }
}
