<?php
/**
 * Run this from `php` container
 */

require_once __DIR__ . '/../app/bootstrap.php';

$di = \Phalcon\Di::getDefault();

$mongo = $di->get('mongo');
/* @var $mongo \MongoDB\Database */

$default_username = 'user@exmaple.com';

$user = \App\Models\Repositories\UserRepository::getInstance()->findFirst([
    'username' => $default_username,
]);
if ($user) {
    echo "Database already seeded" . PHP_EOL;
    return;
}

// @todo this should use UserRepository
$result = $mongo->users->insertOne([
    'username' => $default_username,
    'password' => password_hash('password1', PASSWORD_DEFAULT),
]);

echo "Inserted {$result->getInsertedCount()} users" . PHP_EOL;
