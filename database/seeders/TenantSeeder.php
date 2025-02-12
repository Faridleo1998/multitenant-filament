<?php

namespace Database\Seeders;

use Database\Factories\TenantFactory as Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal() && config('system.factories_count')) {
            Tenant::new()
                ->count(config('system.factories_count'))
                ->create();
        }
    }
}
