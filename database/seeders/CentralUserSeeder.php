<?php

namespace Database\Seeders;

use Database\Factories\CentralUserFactory as CentralUser;
use Illuminate\Database\Seeder;

class CentralUserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal() && config('system.factories_count')) {
            CentralUser::new()
                ->count(config('system.factories_count'))
                ->create();
        }
    }
}
