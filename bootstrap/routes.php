<?php

use Illuminate\Support\Facades\Route;

return function () {
    $centralDomains = config('tenancy.central_domains');

    foreach ($centralDomains as $domain) {
        Route::middleware('web')
            ->domain($domain)
            ->group(base_path('routes/web.php'));
    }

    Route::middleware('web')->group(base_path('routes/tenant.php'));
};
