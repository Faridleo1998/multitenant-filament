<?php

namespace Database\Seeders\Tenant;

use Database\Factories\StudyFactory as Study;
use Illuminate\Database\Seeder;

class StudySeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal() && config('system.factories_count')) {
            Study::new()
                ->count(config('system.factories_count'))
                ->create();
        }
    }
}
