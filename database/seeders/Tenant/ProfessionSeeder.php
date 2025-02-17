<?php

namespace Database\Seeders\Tenant;

use Database\Factories\ProfessionFactory as Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal()) {
            Profession::new()
                ->count(config('system.factories_count'))
                ->create();
        }
    }
}
