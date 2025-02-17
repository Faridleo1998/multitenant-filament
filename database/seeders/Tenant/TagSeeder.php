<?php

namespace Database\Seeders\Tenant;

use Database\Factories\TagFactory as Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal()) {
            Tag::new()
                ->count(config('system.factories_count'))
                ->create();
        }
    }
}
