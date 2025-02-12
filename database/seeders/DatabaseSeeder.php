<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            WorldSeeder::class,
            ShieldSeeder::class,
            CentralUserSeeder::class,
            TenantSeeder::class,
        ]);
    }
}
