<?php

declare(strict_types=1);

namespace App\Providers;

use App\Configuration\SqlConfig;
use App\Shared\Database\Database;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class DatabaseServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id) : bool
    {
        return $id === Database::class;
    }

    public function register() : void
    {
        $container = $this->container;
        /**
         * @var SqlConfig $config
         */
        $config = $container->get(SqlConfig::class);
        $this->container->addShared(
            Database::class,
            function () use ($config) {
                return new Database(
                    $config->host->value(),
                    $config->username->value(),
                    $config->password->value(),
                    $config->database->value()
                );
            }
        );
    }
}
