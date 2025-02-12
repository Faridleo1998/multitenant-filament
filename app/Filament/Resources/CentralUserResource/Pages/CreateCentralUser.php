<?php

namespace App\Filament\Resources\CentralUserResource\Pages;

use App\Filament\Resources\CentralUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCentralUser extends CreateRecord
{
    protected static string $resource = CentralUserResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
