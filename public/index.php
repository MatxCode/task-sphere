<?php

use App\Kernel;
use Symfony\Component\Runtime\SymfonyRuntime;

require dirname(__DIR__).'/vendor/autoload.php';

$_SERVER['APP_RUNTIME'] = SymfonyRuntime::class;
$_SERVER['APP_ENV'] = 'prod';
$_SERVER['APP_DEBUG'] = '0';

$runtime = new SymfonyRuntime([
    'disable_dotenv' => true,
    'project_dir' => dirname(__DIR__)
]);

[$object, $method] = $runtime->getResolver($kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']))->resolve();

exit($runtime->getRunner($object, $method)->run());