<?php

declare(strict_types=1);

use App\Kernel\Kernel;
use Dotenv\Dotenv;
use League\Container\Container;

require '../vendor/autoload.php';

define("ROOT_DIR", dirname(__DIR__));

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

/**
 * @var Container $container
 */
$container = require 'dependencies.php';

Kernel::create()->run();
