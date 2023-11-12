<?php

declare(strict_types=1);

namespace App\Providers;

use App\Configuration\Config;
use App\Configuration\Enviroment;
use App\Configuration\SqlConfig;
use App\Kernel\Kernel;
use App\Shared\Security\OneTimeReadSecuredString;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

final class ConfigurationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function provides(string $id) : bool
    {
        return in_array(
            $id,
            [
                Config::class,
                SqlConfig::class,
            ],
            true
        );
    }

    public function register() : void
    {
        $container = $this->container;

        $container->addShared(Config::class, new Config(Enviroment::from(env('APP_ENV', 'prod'))));
        $container->addShared(
            SqlConfig::class,
            new SqlConfig(
                OneTimeReadSecuredString::make(env('DB_HOST', '')),
                OneTimeReadSecuredString::make(env('DB_USERNAME', '')),
                OneTimeReadSecuredString::make(env('DB_PASSWORD', '')),
                OneTimeReadSecuredString::make(env('DB_NAME', ''))
            )
        );
    }

    public function boot() : void
    {
        Kernel::__setContainer($this->container);
    }
}
