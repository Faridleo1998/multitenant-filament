<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Domain\Tenant\Actions\CreateTenantDomainAction;
use App\Filament\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate()
    {
        $createTenantDomainAction = app(CreateTenantDomainAction::class);
        $tenant = $this->record;
        $createTenantDomainAction->execute($tenant, $tenant->domain);
    }
}
