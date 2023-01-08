<?php

declare(strict_types=1);

use App\Kernel\Http\Controllers\HtmlController;
use App\Kernel\Http\Controllers\RestController;
use App\Kernel\Http\Response\HtmlResponseFactory;
use App\Kernel\Http\Response\RestResponseFactory;
use App\Providers\ConfigurationServiceProvider;
use App\Providers\DatabaseServiceProvider;
use App\Providers\EventDispatcherServiceProvider;
use App\Providers\ExceptionHandlerServiceProvider;
use App\Providers\HttpServiceProvider;
use App\Providers\RouterServiceProvider;
use App\Providers\TemplateServiceProvider;
use League\Container\Container;
use Twig\Environment;

$container = new Container();

/**
 * Service providers
 */
$container->addServiceProvider(new ConfigurationServiceProvider());
$container->addServiceProvider(new RouterServiceProvider());
$container->addServiceProvider(new TemplateServiceProvider());
$container->addServiceProvider(new HttpServiceProvider());
$container->addServiceProvider(new DatabaseServiceProvider());
$container->addServiceProvider(new ExceptionHandlerServiceProvider());
$container->addServiceProvider(new EventDispatcherServiceProvider());

$container->inflector(HtmlController::class)
    ->invokeMethods(
        [
            '__setTwig'            => [Environment::class],
            '__setResponseFactory' => [HtmlResponseFactory::class],
        ]
    )
;

$container->inflector(RestController::class)
    ->invokeMethods(
        [
            '__setResponseFactory' => [RestResponseFactory::class],
        ]
    )
;
$container->delegate(
    new League\Container\ReflectionContainer(true)
);
