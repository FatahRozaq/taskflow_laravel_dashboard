<?php

namespace App\Filament\Resources\ExternalDataResource\Pages;

use App\Filament\Resources\ExternalDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExternalData extends ListRecords
{
    protected static string $resource = ExternalDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
