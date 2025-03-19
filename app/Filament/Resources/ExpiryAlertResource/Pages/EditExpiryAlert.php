<?php

namespace App\Filament\Resources\ExpiryAlertResource\Pages;

use App\Filament\Resources\ExpiryAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpiryAlert extends EditRecord
{
    protected static string $resource = ExpiryAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
