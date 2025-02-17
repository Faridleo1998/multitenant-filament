<?php

namespace App\Filament\Tenant\Resources\TagResource\Pages;

use App\Filament\Tenant\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageTags extends ManageRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->modalWidth(MaxWidth::Small),
        ];
    }
}
