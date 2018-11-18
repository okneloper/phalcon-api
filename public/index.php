<?php

use Phalcon\Mvc\Micro;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once BASE_PATH . '/vendor/autoload.php';

$app = new Micro();

$initializer = new \App\Initializer($app);

$initializer->initializeApplication();

    $app->handle();
try {
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
