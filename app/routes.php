<?php

declare(strict_types=1);

use App\TestingDomain\IndexController;
use League\Route\Router;

/**
 * @var Router $router
 */
$router->map('GET', '', IndexController::class . '::index');


