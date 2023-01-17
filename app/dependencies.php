<?php

declare(strict_types=1);

use App\Console\ConsoleServiceProvider;
use App\Kernel\Http\Controllers\ViewController;
use App\Kernel\Http\Controllers\ApiController;
use App\Kernel\Http\Response\ViewResponseFactory;
use App\Kernel\Http\Response\ApiResponseFactory;
use App\Providers\ConfigurationServiceProvider;
use App\Providers\DatabaseServiceProvider;
use App\Providers\ExceptionHandlerServiceProvider;
use App\Providers\HttpServiceProvider;
use App\Providers\RouterServiceProvider;
use App\Providers\TemplateServiceProvider;
use App\Shared\Event\EventingServiceProvider;
use App\Shared\Identification\IdentificationServiceProvider;
use League\Container\Container;
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
$container->addServiceProvider(new IdentificationServiceProvider());

$container->inflector(ViewController::class)
    ->invokeMethods(
        [
            '__setTwig'            => [Environment::class],
            '__setResponseFactory' => [ViewResponseFactory::class],
        ]
    )
;

$container->inflector(ApiController::class)
    ->invokeMethods(
        [
            '__setResponseFactory' => [ApiResponseFactory::class],
        ]
    )
;
$container->delegate(
    new League\Container\ReflectionContainer(true)
);
