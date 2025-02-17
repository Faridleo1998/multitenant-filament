<?php

namespace App\Filament\Tenant\Resources\CategoryResource\Pages;

use App\Filament\Tenant\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->modalWidth(MaxWidth::Small),
        ];
    }
}
