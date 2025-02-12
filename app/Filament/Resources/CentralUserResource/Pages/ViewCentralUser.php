<?php

namespace App\Filament\Resources\CentralUserResource\Pages;

use App\Filament\Resources\CentralUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCentralUser extends ViewRecord
{
    protected static string $resource = CentralUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
