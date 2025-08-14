<?php

namespace App\Filament\Resources\ExternalDataResource\Pages;

use App\Filament\Resources\ExternalDataResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;

class CreateExternalData extends CreateRecord
{
    protected static string $resource = ExternalDataResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            $response = Http::get($data['url']);

            if ($response->failed()) {
                throw new \Exception("Failed to fetch data: " . $response->body());
            }

            $data['data'] = $response->json();
            $data['last_synced_at'] = now();
            $data['status'] = 'success';
            $data['error_message'] = null;
        } catch (\Throwable $e) {
            $data['data'] = [];
            $data['last_synced_at'] = now();
            $data['status'] = 'failed';
            $data['error_message'] = $e->getMessage();
        }

        return $data;
    }
}
