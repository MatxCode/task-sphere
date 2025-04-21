<?php

// Solution Nucléaire - Remplacement complet du système Dotenv
$_SERVER = array_merge([
    'APP_ENV' => 'prod',
    'APP_DEBUG' => '0',
    'SYMFONY_DOTENV_VARS' => false
], $_SERVER);

// Désactive explicitement le chargement des .env
putenv('SYMFONY_DOTENV_VARS=false');