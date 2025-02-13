<?php

declare(strict_types=1);

use App\Providers\TenancyServiceProvider;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    TenancyServiceProvider::TENANCY_IDENTIFICATION,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return to_route('filament.tenant.pages.dashboard');
    });
});
