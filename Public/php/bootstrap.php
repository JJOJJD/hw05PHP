<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'   => 'pgsql',
    'host'     => $_ENV['DB_HOST']     ?? getenv('DB_HOST'),
    'port'     => $_ENV['DB_PORT']     ?? getenv('DB_PORT') ?: '5432',
    'database' => $_ENV['DB_NAME']     ?? getenv('DB_NAME'),
    'username' => $_ENV['DB_USER']     ?? getenv('DB_USER'),
    'password' => $_ENV['DB_PASS']     ?? getenv('DB_PASS'),
    'charset'  => 'utf8',
    'prefix'   => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
