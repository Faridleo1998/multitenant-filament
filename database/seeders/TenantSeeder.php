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
        if (app()->isLocal()) {

            $tenant = Tenant::new()->create([
                'identification' => '1234567890',
                'name' => 'praxis',
                'domain' => 'praxis',
                'phone' => '1234567890',
            ]);

            $tenant->domains()->create([
                'tenant_id' => $tenant->id,
                'domain' => $tenant->domain . '.' . config('system.domain'),
            ]);

            if (config('system.factories_count')) {
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
}
