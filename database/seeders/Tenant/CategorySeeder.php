<?php

namespace Database\Seeders\Tenant;

use Database\Factories\CategoryFactory as Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal() && config('system.factories_count')) {
            Category::new()
                ->count(config('system.factories_count'))
                ->create();
        }
    }
}
