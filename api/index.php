<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

// Use /tmp for storage
$app->useStoragePath('/tmp/storage');

// Force bootstrap/cache path to /tmp/bootstrap/cache
$bootstrapCache = '/tmp/bootstrap/cache';
if (!is_dir($bootstrapCache)) {
    mkdir($bootstrapCache, 0777, true);
}

// Patch the PackageManifest to use /tmp/bootstrap/cache
$app->make(Illuminate\Foundation\PackageManifest::class)
    ->manifestPath = $bootstrapCache . '/services.php';

// Ensure storage/framework directories exist
$frameworkDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
];

foreach ($frameworkDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Handle the request
$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate($request, $response);
