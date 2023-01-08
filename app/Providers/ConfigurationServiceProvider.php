<?php

declare(strict_types=1);

namespace App\Providers;

use App\Configuration\Config;
use App\Configuration\Enviroment;
use App\Kernel\Kernel;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

final class ConfigurationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function provides(string $id) : bool
    {
        return $id === Config::class;
    }

    public function register() : void
    {
        $container = $this->container;

        $config = new Config(Enviroment::from(env('APP_ENV', 'prod')));

        $container->addShared(Config::class, $config);
    }

    public function boot() : void
    {
        Kernel::__setContainer($this->container);
    }
}
