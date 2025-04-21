<?php

use App\Kernel;

// Solution Nucléaire - Contournement total de Dotenv
$_SERVER['APP_ENV'] = 'prod';
$_SERVER['APP_DEBUG'] = '0';
$_ENV['APP_ENV'] = 'prod';
$_ENV['APP_DEBUG'] = '0';
putenv('APP_ENV=prod');
putenv('APP_DEBUG=0');

// Chargement DIRECT sans autoload_runtime
require dirname(__DIR__).'/vendor/autoload.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$kernel->boot();
// ... reste du code