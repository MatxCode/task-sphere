<?php

use App\Kernel;

// Configuration forcée pour Railway
$_ENV['APP_ENV'] = $_SERVER['APP_ENV'] = 'prod';
$_ENV['APP_DEBUG'] = $_SERVER['APP_DEBUG'] = '0';
putenv('APP_ENV=prod');

// Désactivation du chargement .env si le fichier n'existe pas
if (!file_exists(dirname(__DIR__).'/.env')) {
    $_SERVER['SYMFONY_DOTENV_VARS'] = false;
}

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};