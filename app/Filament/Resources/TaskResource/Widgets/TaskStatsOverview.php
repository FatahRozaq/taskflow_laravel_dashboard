<?php

namespace App\Filament\Resources\TaskResource\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tasks', Task::count())
                ->description('All tasks in the system')
                ->color('primary'),

            Stat::make('Completed Tasks', Task::where('status', 'Done')->count())
                ->description('Tasks marked as completed')
                ->color('success'),

            Stat::make('Pending Tasks', Task::where('status', 'In Progress')->count())
                ->description('Tasks still in progress')
                ->color('warning'),
        ];
    }
}
