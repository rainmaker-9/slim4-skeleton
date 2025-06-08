<?php

declare(strict_types=1);

/**
 * This file is part of the PHP Application Skeleton.
 *
 * (c) Raviraj Chougale <contact@ravirajchougale.com>
 */

// Autoload dependencies using Composer
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
  throw new RuntimeException('The vendor/autoload.php file does not exist. Please run "composer install" to install dependencies.');
}
require __DIR__ . '/vendor/autoload.php';

// Load environment variables from .env file
if (!class_exists('Dotenv\Dotenv')) {
  throw new RuntimeException('The Dotenv package is not installed. Please run "composer install" to install dependencies.');
}
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// Set the default timezone
date_default_timezone_set(\App\Core\Helpers::env('APP_TIMEZONE', 'UTC'));

// Set the default locale
setlocale(LC_ALL, \App\Core\Helpers::env('APP_LOCALE', 'en_US.UTF-8'));

// Set the default character encoding
mb_internal_encoding(\App\Core\Helpers::env('APP_CHARSET', 'UTF-8'));

// Set the default error reporting level
error_reporting(\App\Core\Helpers::env('APP_DEBUG', true) ? E_ALL : 0);

// Set the default headers for CORS
header("Access-Control-Allow-Credentials: true");

// Allow specific headers and methods for CORS
header("Access-Control-Allow-Headers: Content-Type, Authorization, Origin, X-Requested-With, X-CSRF-Token, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific origin for CORS
if (\App\Core\Helpers::env('APP_ENV') === 'production') {
  header("Access-Control-Allow-Origin: " . \App\Core\Helpers::env('APP_URL'));
} else {
  header("Access-Control-Allow-Origin: *");
}

// Define the application root directory
defined('APP_ROOT') || define('APP_ROOT', __DIR__);

/**
 * Bootstrap the application.
 * This includes loading the application configuration,
 * setting up the service container, and registering middleware and routes.
 * @var \Slim\App $app The Slim application instance
 * @return void
 * @throws RuntimeException if the application bootstrap fails
 */
$app = require __DIR__ . '/bootstrap/app.php';

(require __DIR__ . '/bootstrap/middleware.php')($app);

(require __DIR__ . '/routes/web.php')($app);

$app->run();
