<?php

namespace Database\Seeders;

use App\Domain\Tenant\Models\Domain;
use App\Domain\Tenant\Models\Tenant as TenantModel;
use Database\Factories\TenantFactory as Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal() && config('system.factories_count')) {
            Tenant::new()
                ->count(config('system.factories_count'))
                ->create()
                ->each(
                    function (TenantModel $tenant): void {
                        Domain::create([
                            'tenant_id' => $tenant->id,
                            'domain' => $tenant->domain,
                        ]);
                    }
                );
        }
    }
}
