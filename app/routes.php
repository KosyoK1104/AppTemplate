<?php

declare(strict_types=1);

use App\TestingFolder\IndexController;
use League\Route\Router;

/**
 * @var Router $router
 */
$router->map('GET', '', IndexController::class . '::index');


