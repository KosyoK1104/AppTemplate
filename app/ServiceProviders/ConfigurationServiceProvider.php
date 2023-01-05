<?php

declare(strict_types=1);

namespace App\ServiceProviders;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

final class ConfigurationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

    public function boot() : void
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();
    }

    public function provides(string $id) : bool
    {
        return false;
    }

    public function register() : void
    {
        //
    }
}
