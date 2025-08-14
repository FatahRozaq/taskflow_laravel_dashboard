<?php

namespace App\Filament\Resources\ExternalDataResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\ExternalData;

class WeatherStatus extends Widget
{
    protected static string $view = 'filament.resources.external-data-resource.widgets.weather-status';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $record = ExternalData::where('source', 'openweather')
            ->where('type', 'weather')
            ->latest('last_synced_at')
            ->first();

        if (!$record || empty($record->data)) {
            return [
                'city' => null,
                'temp' => null,
                'desc' => null,
                'icon' => null,
                'last_sync' => null,
                'status' => $record?->status ?? 'No Data',
            ];
        }

        $data = $record->data;

        return [
            'city' => $data['name'] ?? '-',
            'temp' => $data['main']['temp'] ?? null,
            'desc' => ucfirst($data['weather'][0]['description'] ?? '-'),
            'icon' => $data['weather'][0]['icon'] ?? null,
            'last_sync' => $record->last_synced_at,
            'status' => $record->status,
        ];
    }
}
