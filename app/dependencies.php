<?php

declare(strict_types=1);

use App\Kernel\Http\Controllers\HtmlController;
use App\Kernel\Http\Controllers\RestController;
use App\Kernel\Http\Response\HtmlResponseFactory;
use App\Kernel\Http\Response\RestResponseFactory;
use App\ServiceProviders\ConfigurationServiceProvider;
use App\ServiceProviders\DatabaseServiceProvider;
use App\ServiceProviders\HttpServiceProvider;
use App\ServiceProviders\RouterServiceProvider;
use App\ServiceProviders\TemplateServiceProvider;
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

return $container;
