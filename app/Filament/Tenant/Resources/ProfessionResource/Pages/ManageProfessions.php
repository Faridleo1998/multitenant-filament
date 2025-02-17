<?php

namespace App\Filament\Tenant\Resources\ProfessionResource\Pages;

use App\Filament\Tenant\Resources\ProfessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageProfessions extends ManageRecords
{
    protected static string $resource = ProfessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::Small),
        ];
    }
}
