<?php

namespace App\Filament\Resources\ExternalDataResource\Pages;

use App\Filament\Resources\ExternalDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExternalData extends EditRecord
{
    protected static string $resource = ExternalDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
