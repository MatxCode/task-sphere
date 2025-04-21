<?php

use App\Kernel;

// Désactivation FORCÉE de Dotenv
putenv('SYMFONY_DOTENV_VARS=0');
$_SERVER['SYMFONY_DOTENV_VARS'] = '0';

// Configuration FORCÉE en production
$_SERVER['APP_ENV'] = 'prod';
$_SERVER['APP_DEBUG'] = '0';
putenv('APP_ENV=prod');
putenv('APP_DEBUG=0');

// Chargement MANUEL sans autoload_runtime.php
require dirname(__DIR__).'/vendor/autoload.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$kernel->boot();
$response = $kernel->handle(/* ... */);
$response->send();