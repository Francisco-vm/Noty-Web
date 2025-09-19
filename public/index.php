<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

define('BASE_PATH', dirname(__DIR__));
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');

require_once __DIR__ . '/../App/Routes/web.php';