<?php

use Symfony\Component\Runtime\Runtime;
use Symfony\Component\Runtime\SymfonyRuntime;

class CustomRuntime extends SymfonyRuntime
{
    public function __construct(array $options = [])
    {
        // Force le mode production et désactive Dotenv
        $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'prod';
        $_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = '0';
        $options['disable_dotenv'] = true;

        parent::__construct($options);
    }
}

// Enregistre notre Runtime personnalisé
return [
    Runtime::class => CustomRuntime::class
];