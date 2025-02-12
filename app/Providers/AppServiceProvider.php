<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());
        URL::forceHttps(app()->isProduction());
        $this->shouldBeStrict(app()->isLocal());
    }

    public function shouldBeStrict($appIsLocal)
    {
        Model::preventLazyLoading($appIsLocal);
        Model::preventAccessingMissingAttributes($appIsLocal);
    }
}
