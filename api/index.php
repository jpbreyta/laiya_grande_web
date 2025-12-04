<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

// --------------------
// VERCEL SERVERLESS FIX
// --------------------

// 1. Use /tmp for storage
$app->useStoragePath('/tmp/storage');

// 2. Create /tmp/bootstrap/cache and patch PackageManifest
$bootstrapCache = '/tmp/bootstrap/cache';
if (!is_dir($bootstrapCache)) {
    mkdir($bootstrapCache, 0777, true);
}

// Patch PackageManifest to write manifest to /tmp
$packageManifest = $app->make(Illuminate\Foundation\PackageManifest::class);
$packageManifest->manifestPath = $bootstrapCache . '/services.php';

// 3. Ensure storage/framework directories exist
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

// --------------------
// HANDLE THE REQUEST
// --------------------

$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate($request, $response);
