<?php

namespace App\Filament\Resources\ExpiryAlertResource\Pages;

use App\Filament\Resources\ExpiryAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpiryAlerts extends ListRecords
{
    protected static string $resource = ExpiryAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
