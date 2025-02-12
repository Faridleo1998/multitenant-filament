<?php

namespace App\Providers;

use BezhanSalleh\FilamentShield\FilamentShield;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->shield();
    }

    public function shield(): void
    {
        FilamentShield::prohibitDestructiveCommands(app()->isProduction());
    }
}
