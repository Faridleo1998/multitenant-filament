<?php

namespace Database\Seeders;

use Database\Factories\AdminFactory as Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::new()->create([
            'name' => 'Super Admin',
            'email' => 'super_admin@' . config('system.domain'),
            'password' => Hash::make('admin'),
        ]);

        if (app()->isLocal()) {
            Admin::new()->create([
                'name' => 'Test Test',
                'email' => 'test@' . config('system.domain'),
                'password' => Hash::make('test'),
            ]);

            if (config('system.factories_count')) {
                Admin::new()
                    ->count(config('system.factories_count'))
                    ->create();
            }
        }
    }
}
