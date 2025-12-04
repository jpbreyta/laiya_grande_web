<?php

require __DIR__ . '/../vendor/autoload.php';

$bootstrapCache = '/tmp/bootstrap/cache';
if (!is_dir($bootstrapCache)) {
    mkdir($bootstrapCache, 0777, true);
}

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

$app = require __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath('/tmp/storage');

$packageManifest = $app->make(Illuminate\Foundation\PackageManifest::class);
$packageManifest->manifestPath = $bootstrapCache . '/services.php';

$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate($request, $response);
