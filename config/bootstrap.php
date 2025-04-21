<?php

// Désactive COMPLÈTEMENT Dotenv
if (!isset($_SERVER['APP_ENV'])) {
    $_SERVER['APP_ENV'] = 'prod';
    $_SERVER['APP_DEBUG'] = '0';
}