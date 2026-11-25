<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
| Laravel qovluğunun yeri:
|  - Adi halda (və ya public_html → laravel/public simvolik linki ilə): bu faylın bir üst qovluğu
|  - "Ayrılmış" quraşdırmada (public faylları public_html-də, tətbiq isə ../laravel qovluğunda):
|    ~/domains/hamid.agency/public_html/index.php  →  ~/domains/hamid.agency/laravel
*/
$basePath = file_exists(__DIR__.'/../laravel/bootstrap/app.php')
    ? __DIR__.'/../laravel'
    : __DIR__.'/..';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $basePath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $basePath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $basePath.'/bootstrap/app.php';

// public_path() həmişə bu faylın olduğu qovluğu göstərsin (build/, uploads/ üçün)
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
