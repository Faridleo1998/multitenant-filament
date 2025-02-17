<?php

namespace App\Filament\Tenant\Resources\KeywordResource\Pages;

use App\Filament\Tenant\Resources\KeywordResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageKeywords extends ManageRecords
{
    protected static string $resource = KeywordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::Small),
        ];
    }
}
