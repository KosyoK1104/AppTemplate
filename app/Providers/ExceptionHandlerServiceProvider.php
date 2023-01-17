<?php

declare(strict_types=1);

namespace App\Providers;

use App\Configuration\Config;
use App\Kernel\Http\Handlers\ExceptionHandler;
use App\Kernel\Http\Handlers\ExceptionHandlerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Message\StreamFactoryInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

final class ExceptionHandlerServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id) : bool
    {
        return in_array(
            $id,
            [
                Run::class,
                ExceptionHandlerInterface::class,
            ],
            true
        );
    }

    public function register() : void
    {
        $container = $this->container;
        $container->addShared(Run::class, function () {
            $whoops = new Run();
            $prettyPageHandler = new PrettyPageHandler;
            $this->blacklist($prettyPageHandler);
            $whoops->pushHandler($prettyPageHandler);
            return $whoops;
        });
        $container->addShared(ExceptionHandlerInterface::class, ExceptionHandler::class)
            ->addArgument(Config::class)
            ->addArgument(Run::class)
            ->addArgument(StreamFactoryInterface::class)
        ;
    }

    private function blacklist(PrettyPageHandler $handler) : void
    {
        $blacklist = [
            'DB_HOST',
            'DB_NAME',
            'DB_USERNAME',
            'DB_PASSWORD',
        ];

        foreach ($blacklist as $value) {
            $handler->blacklist('_SERVER', $value);
            $handler->blacklist('_ENV', $value);
        }
    }
}
