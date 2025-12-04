<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

// Force storage to /tmp
$app->useStoragePath('/tmp/storage');

// Force bootstrap/cache to /tmp/bootstrap/cache
define('LARAVEL_BOOTSTRAP_CACHE', '/tmp/bootstrap/cache');

if (!is_dir('/tmp/bootstrap/cache')) {
    mkdir('/tmp/bootstrap/cache', 0777, true);
}
if (!is_dir('/tmp/storage/framework/views')) {
    mkdir('/tmp/storage/framework/views', 0777, true);
}
if (!is_dir('/tmp/storage/framework/cache')) {
    mkdir('/tmp/storage/framework/cache', 0777, true);
}
if (!is_dir('/tmp/storage/framework/sessions')) {
    mkdir('/tmp/storage/framework/sessions', 0777, true);
}

// Set the path for the PackageManifest early
$app->useStoragePath('/tmp/storage');
$app->useCachedPackagesPath(LARAVEL_BOOTSTRAP_CACHE);

$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate($request, $response);
