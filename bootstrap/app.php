<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

$storagePath = '/tmp/storage';
$bootstrapPath = '/tmp/bootstrap';
$bootstrapCache = $bootstrapPath.'/cache';

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

$builder = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    });

// Patch paths on the builder before create()
$builder->bootstrapPath = $bootstrapPath;
$builder->storagePath = $storagePath;

return $builder->create();
