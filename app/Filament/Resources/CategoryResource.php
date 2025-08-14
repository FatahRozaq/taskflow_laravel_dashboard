<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->helperText('Fill the category name')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('description')
                    ->helperText('Fill the description of category'),

                ColorPicker::make('color')
                    ->label('Pilih Warna')
                    ->default('#3b82f6')
                    ->required(),

                Toggle::make('is_default')
                    ->label('Default?')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $oldDefault = \App\Models\Category::where('is_default', true)
                                ->where('category_id', '!=', $get('category_id'))
                                ->first();

                            if ($oldDefault) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Change Default Category?')
                                    ->body("Currently, '{$oldDefault->name}' is the default. Do you want to replace it?")
                                    ->actions([
                                        \Filament\Notifications\Actions\Action::make('yes')
                                            ->label('Yes')
                                            ->color('success')
                                            ->close()
                                            ->action(function () use ($set, $get, $oldDefault) {
                                                $oldDefault->update(['is_default' => false]);
                                                $set('is_default', true);
                                            }),
                                        \Filament\Notifications\Actions\Action::make('no')
                                            ->label('No')
                                            ->color('secondary')
                                            ->close()
                                            ->action(function () use ($set) {
                                                $set('is_default', false);
                                            }),
                                    ])
                                    ->persistent()
                                    ->send();
                            }
                        }
                    })


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                ColorColumn::make('color')
                    ->sortable(),

                IconColumn::make('is_default')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
            ])
            ->headerActions([
                ExportAction::make('Export Excel')
                    ->exports([
                        ExcelExport::make('export_excel')
                            ->fromTable()
                            ->withFilename(fn($resource) => 'Categories ' . now()->format('Y-m-d'))
                            ->withWriterType(Excel::XLSX),

                        ExcelExport::make('export_csv')
                            ->fromTable()
                            ->withFilename('Categories.csv')
                            ->withWriterType(Excel::CSV),
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('setDefault')
                    ->label('Set as Default')
                    ->requiresConfirmation()
                    ->modalHeading('Change Default Category')
                    ->modalSubheading(function ($record) {
                        $oldDefault = \App\Models\Category::where('is_default', true)
                            ->where('category_id', '!=', $record->category_id)
                            ->first();

                        return $oldDefault
                            ? "Currently, '{$oldDefault->name}' is the default category. Do you want to replace it?"
                            : 'This will set this category as the default.';
                    })
                    ->modalButton('Yes, change default')
                    ->action(function ($record) {
                        // Nonaktifkan default lama
                        \App\Models\Category::where('is_default', true)
                            ->update(['is_default' => false]);

                        // Set default baru
                        $record->update(['is_default' => true]);
                    })
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
