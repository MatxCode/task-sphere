<?php

use Symfony\Component\Runtime\SymfonyRuntime;

return [
    SymfonyRuntime::class => [
        'disable_dotenv' => true,
        'env_var_name' => 'APP_ENV',
        'debug_var_name' => 'APP_DEBUG',
        'prod_envs' => ['prod'],
    ]
];