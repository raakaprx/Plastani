<?php
require 'vendor/autoload.php';

$app = new Illuminate\Foundation\Application(__DIR__);
$app->instance('path.config', __DIR__ . '/config');
$app->instance('path.database', __DIR__ . '/database');
$app->registerConfiguredProviders();
echo 'ok';
