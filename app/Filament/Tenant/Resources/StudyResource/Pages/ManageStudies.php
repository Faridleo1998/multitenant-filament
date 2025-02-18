<?php

namespace App\Filament\Tenant\Resources\StudyResource\Pages;

use App\Filament\Tenant\Resources\StudyResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageStudies extends ManageRecords
{
    protected static string $resource = StudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::Small),
        ];
    }
}
