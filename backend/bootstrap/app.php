<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Ensure SQLite database file exists before Laravel boots
$basePath = dirname(__DIR__);
$dbPath = $basePath . '/database/database.sqlite';
if (!file_exists($dbPath)) {
    $dbDir = dirname($dbPath);
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }
    touch($dbPath);
    chmod($dbPath, 0666);
}

return Application::configure(basePath: $basePath)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
