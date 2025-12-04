<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$storagePath = '/tmp/storage';
$bootstrapCache = '/tmp/bootstrap/cache';

$dirs = [
    $bootstrapCache,
    $storagePath.'/framework/views',
    $storagePath.'/framework/cache',
    $storagePath.'/framework/sessions',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    });

$app->useStoragePath($storagePath);
$app->instance('path.bootstrap', '/tmp/bootstrap');

return $app->create();
