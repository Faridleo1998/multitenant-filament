<?php

namespace App\Domain\Tenant\Actions;

use App\Domain\Tenant\Models\Tenant;

class CreateTenantDomainAction
{
    public function execute(Tenant $tenant, string $domain): void
    {
        $tenant->domain()->create([
            'domain' => $domain,
        ]);
    }
}
