<?php

namespace Database\Seeders\Tenant;

use Database\Factories\KeywordFactory as Keyword;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal()) {
            Keyword::new()
                ->count(config('system.factories_count'))
                ->create();
        }
    }
}
