<?php

use App\Kernel;

// Chargez d'abord notre bootstrap personnalisé
require dirname(__DIR__).'/config/bootstrap.php';

// Chargement manuel sans autoload_runtime
require dirname(__DIR__).'/vendor/autoload.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$kernel->boot();
// ... reste du code