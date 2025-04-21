<?php

use Symfony\Component\Dotenv\Dotenv;

// Désactive complètement Dotenv si en production
if (!isset($_SERVER['APP_ENV'])) {
    $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'prod';
    $_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = '0';
    return;
}

// Charge .env seulement en développement
if (!class_exists(Dotenv::class)) {
    return;
}

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');