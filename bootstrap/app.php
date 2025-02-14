<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        then: include 'routes.php',
        health: '/up',
    )
    ->withMiddleware(include 'middlewares.php')
    ->withExceptions(include 'exceptions.php')
    ->create();
