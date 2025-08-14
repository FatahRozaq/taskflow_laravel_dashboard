<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExternalDataResource\Pages;
use App\Models\ExternalData;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class ExternalDataResource extends Resource
{
    protected static ?string $model = ExternalData::class;

    protected static ?string $navigationIcon = 'heroicon-o-cloud';

    protected static ?string $navigationLabel = 'External Data';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('url')
                ->label('API URL')
                ->required()
                ->placeholder('https://api.openweathermap.org/data/2.5/weather?q=Jakarta&appid=API_KEY&units=metric'),
            Forms\Components\TextInput::make('source')->default('openweather')->required(),
            Forms\Components\TextInput::make('type')->default('weather')->required(),
            Forms\Components\DateTimePicker::make('last_synced_at')->disabled(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'success' => 'Success',
                    'failed' => 'Failed',
                ])
                ->disabled(),
            Forms\Components\Textarea::make('error_message')->rows(3)->disabled(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source')->sortable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('last_synced_at')->dateTime()->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'success',
                        'failed' => 'danger',
                        'pending' => 'warning',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('syncWeather')
                    ->label('Sync Now')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function () {
                        $record = ExternalData::firstOrNew([
                            'source' => 'openweather',
                            'type'   => 'weather',
                        ]);

                        try {
                            if (!$record->url) {
                                throw new \Exception('API URL belum diisi.');
                            }

                            $response = Http::get($record->url);

                            if ($response->failed()) {
                                throw new \Exception("Failed to fetch data: " . $response->body());
                            }

                            $record->data = $response->json();
                            $record->last_synced_at = now();
                            $record->status = 'success';
                            $record->error_message = null;
                            $record->save();

                        } catch (\Throwable $e) {
                            $record->data = [];
                            $record->last_synced_at = now();
                            $record->status = 'failed';
                            $record->error_message = $e->getMessage();
                            $record->save();
                        }
                    })
                    ->requiresConfirmation()
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExternalData::route('/'),
            'create' => Pages\CreateExternalData::route('/create'),
            'edit' => Pages\EditExternalData::route('/{record}/edit'),
        ];
    }
}
