<?php

declare(strict_types=1);

use App\Console\ConsoleServiceProvider;
use App\Kernel\Http\Controllers\Controller;
use App\Kernel\Http\Controllers\ViewControllerInterface;
use App\Kernel\Http\Response\ViewResponseFactory;
use App\Kernel\Http\Response\ApiResponseFactory;
use App\Providers\ConfigurationServiceProvider;
use App\Providers\DatabaseServiceProvider;
use App\Providers\ExceptionHandlerServiceProvider;
use App\Providers\HttpServiceProvider;
use App\Providers\RouterServiceProvider;
use App\Providers\TemplateServiceProvider;
use App\Shared\ContainerAware\ContainerAwareInterface;
use App\Shared\Event\EventingServiceProvider;
use League\Container\Container;
use Psr\Container\ContainerInterface;
use Twig\Environment;

$container = new Container();

/**
 * Service providers
 */
$container->addServiceProvider(new ConfigurationServiceProvider());
$container->addServiceProvider(new ConsoleServiceProvider());
$container->addServiceProvider(new RouterServiceProvider());
$container->addServiceProvider(new TemplateServiceProvider());
$container->addServiceProvider(new HttpServiceProvider());
$container->addServiceProvider(new DatabaseServiceProvider());
$container->addServiceProvider(new ExceptionHandlerServiceProvider());
$container->addServiceProvider(new EventingServiceProvider());

$container->inflector(Controller::class)
    ->invokeMethods(
        [
            '__setApiResponseFactory' => [ApiResponseFactory::class],
        ]
    )
;

$container->inflector(ViewControllerInterface::class)
    ->invokeMethods(
        [
            '__setTwig'                => [Environment::class],
            '__setViewResponseFactory' => [ViewResponseFactory::class],
        ]
    )
;

$container->inflector(ContainerAwareInterface::class)
    ->invokeMethods(['__setContainer' => [$container]])
;
$container->delegate(
    new League\Container\ReflectionContainer(true)
);
