<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'   => 'pgsql',
    'host'     => getenv('DB_HOST')     ?: '',
    'port'     => getenv('DB_PORT')     ?: '5432',
    'database' => getenv('DB_NAME')     ?: '',
    'username' => getenv('DB_USER')     ?: '',
    'password' => getenv('DB_PASS')     ?: '',
    'charset'  => 'utf8',
    'prefix'   => '',
    'sslmode'  => 'require',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
