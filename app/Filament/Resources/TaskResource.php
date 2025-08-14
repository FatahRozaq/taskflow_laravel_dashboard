<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Filament\Resources\TaskResource\Widgets\TaskStatsOverview;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Task Overview';
    protected static ?string $navigationGroup = 'Tasks';

    public static function getWidgets(): array
    {
        return [
            TaskStatsOverview::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'In Progress' => 'warning',
                        'overdue' => 'danger',
                        default => 'secondary', // optional fallback
                    }),
                TextColumn::make('priority')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->date()
                    ->sortable(),
            ])
            ->headerActions([
                ExportAction::make('Export Excel')
                    ->exports([
                        ExcelExport::make('export_excel')
                            ->fromTable()
                            ->withFilename(fn($resource) => 'Task ' . now()->format('Y-m-d'))
                            ->withWriterType(Excel::XLSX),

                        ExcelExport::make('export_csv')
                            ->fromTable()
                            ->withFilename('Task.csv')
                            ->withWriterType(Excel::CSV),
                    ]),
            ])
            ->defaultSort('due_date', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'in_progress' => 'In Progress',
                        'overdue' => 'Overdue',
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make('Export Selected'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Nonaktifkan tombol Create
    }
}
