<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

define("ROOT_DIR", dirname(__DIR__));

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();
require 'dependencies.php';
