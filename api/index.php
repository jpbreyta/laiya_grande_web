<?php

require __DIR__ . '/../vendor/autoload.php';

// --------------------
// VERCEL SERVERLESS FIX
// --------------------

// Ensure /tmp/bootstrap/cache exists
$bootstrapCache = '/tmp/bootstrap/cache';
if (!is_dir($bootstrapCache)) {
    mkdir($bootstrapCache, 0777, true);
}

// Ensure /tmp/storage/framework directories exist
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

// Define a bootstrap path that points to /tmp
$appBootstrapPath = __DIR__ . '/../bootstrap';
$appBootstrapCachePath = '/tmp/bootstrap/cache';

// --------------------
// Load Laravel App
// --------------------
$app = require $appBootstrapPath . '/app.php';

// Force storage path
$app->useStoragePath('/tmp/storage');

// Patch bootstrap/cache for ProviderRepository and PackageManifest
$app->useStoragePath('/tmp/storage'); // storage override
$app->instance('path.bootstrap', '/tmp/bootstrap'); // patch bootstrap path

// --------------------
// HANDLE THE REQUEST
// --------------------
$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate($request, $response);
