<?php

namespace App\Filament\Resources\CentralUserResource\Pages;

use App\Filament\Resources\CentralUserResource;
use Filament\Resources\Pages\EditRecord;

class EditCentralUser extends EditRecord
{
    protected static string $resource = CentralUserResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    public function getContentTabLabel(): ?string
    {
        return ucfirst(self::$resource::getModelLabel());
    }
}
