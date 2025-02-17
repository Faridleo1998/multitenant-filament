<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ShieldSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            KeywordSeeder::class,
            ProfessionSeeder::class,
        ]);
    }
}
