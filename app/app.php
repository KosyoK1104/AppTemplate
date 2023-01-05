<?php

declare(strict_types=1);

use App\Kernel\Kernel;
use Dotenv\Dotenv;

require '../vendor/autoload.php';

define("ROOT_DIR", dirname(__DIR__));

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

Kernel::__setContainer(require 'dependencies.php');

$kernel = Kernel::create();

$kernel->run();
