<?php

namespace Database\Seeders\Tenant;

use Database\Factories\CategoryFactory as Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal()) {
            Category::new()
                ->count(config('system.factories_count'))
                ->create();
        }
    }
}
