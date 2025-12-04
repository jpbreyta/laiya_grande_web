<?php

// 1. Loading the Laravel app
$app = require __DIR__ . '/../bootstrap/app.php';

// 2. FORCE Storage Path to /tmp (The only writable directory on Vercel)
// This fixes the "Read-only file system" error for caches and views
$app->useStoragePath('/tmp/storage');

// 3. Handle the incoming request
$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);

$response->send();

$app->terminate($request, $response);
