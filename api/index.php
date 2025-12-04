<?php

// 1. Register the Composer Autoloader (MISSING LINE FIXED HERE)
// This loads all the libraries in the 'vendor' folder so Laravel can use them.
require __DIR__ . '/../vendor/autoload.php';

// 2. Load the Laravel App
$app = require __DIR__ . '/../bootstrap/app.php';

// 3. FORCE Storage Path to /tmp
// Vercel only allows writing to /tmp, so we redirect storage there.
$app->useStoragePath('/tmp/storage');

// 4. Handle the Request
$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);

$response->send();

$app->terminate($request, $response);
