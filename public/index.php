<?php

require_once __DIR__ . '/../tinypast/Psr4Autoloader.php';

$loader = new \Tinypast\Psr4Autoloader();
$loader->register();
$loader->addNamespace('Foundation', '../tinypast/foundation');
$loader->addNamespace('App', '../app');

use Foundation\Route;

// .env Load
Foundation\Config\DotEnv::load(__DIR__ . '/../.env');

// Set default timezone
date_default_timezone_set(
    Foundation\Config\DotEnv::get('DEFAUT_TIMEZONE', 'UTC')
);

// XML로부터 route 정보를 Load하여 등록한다.
$xml = simplexml_load_file(__DIR__ . '/../routes.xml');

// web route 설정
foreach ($xml->routes->web->children() as $route) {
    $attr = $route->attributes();
    Route::add(
        (string) $attr->url,
        (string) $attr->controller,
        (string) $attr->method
    );
}

// error handler 설정
foreach ($xml->errors->children() as $error) {
    $attr = $error->attributes();
    switch ((int) $attr->code) {
        // 404 Not found
        case 404:
            Route::notFound((string) $attr->controller);
            break;

        // 405 Method Not Allowed
        case 405:
            Route::methodNotAllowed((string) $attr->controller);
            break;

        default:
            break;
    }
}

// Base Url 정보 설정
$baseUrl = isset($xml->routes->attributes()->baseUrl)
    ? (string) $xml->routes->attributes()->baseUrl
    : '/';

// Route 실행
Route::run($baseUrl);
