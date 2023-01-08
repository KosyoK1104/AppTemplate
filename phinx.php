<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotEnv = Dotenv::createImmutable(__DIR__);
$dotEnv->load();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'dbx_migrations',
        'default_environment' => 'local',
        'prod' => [
            'adapter' => 'mysql',
            'host' => env('DB_HOST'),
            'name' => env('DB_NAME'),
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'dev' => [
            'adapter' => 'mysql',
            'host' => env('DB_HOST'),
            'name' => env('DB_NAME'),
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'local' => [
            'adapter' => 'mysql',
            'host' => env('DB_HOST'),
            'name' => env('DB_NAME'),
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
