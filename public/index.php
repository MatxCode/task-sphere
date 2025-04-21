<?php

use App\Kernel;

// Force les valeurs avant tout chargement
$_SERVER['APP_ENV'] = 'prod';
$_SERVER['APP_DEBUG'] = '0';

require dirname(__DIR__).'/vendor/autoload.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$kernel->boot();
// ... reste inchangé