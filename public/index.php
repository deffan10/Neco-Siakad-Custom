<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Force HTTPS If access from cloudflare
if (isset($_SERVER['HTTP_CF_VISITOR']) && str_contains($_SERVER['HTTP_CF_VISITOR'], '"scheme":"https"')) {
    $_SERVER['HTTPS'] = 'on';
}

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
