<?php

use Illuminate\Foundation\Configuration\Middleware;

return function (Middleware $middleware) {
    $middleware->group('universal', []);
};
