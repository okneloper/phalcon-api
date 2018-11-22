<?php

use Phalcon\Mvc\Micro;

$app = require_once __DIR__ . '/../app/bootstrap.php';

    $app->handle();
try {
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
