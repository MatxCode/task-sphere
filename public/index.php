<?php
use App\Kernel;

// Solution radicale
$_SERVER['SYMFONY_DOTENV_VARS'] = '0'; // Désactive Dotenv
$_ENV['APP_ENV'] = $_SERVER['APP_ENV'] = 'prod';
$_ENV['APP_DEBUG'] = $_SERVER['APP_DEBUG'] = '0';

require dirname(__DIR__).'/vendor/autoload.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$kernel->boot();