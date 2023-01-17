<?php

declare(strict_types=1);

namespace App\Providers;

use App\Kernel\Http\Middlewares\DatabaseMiddleware;
use App\Kernel\Http\Middlewares\ExceptionHandlerMiddleware;
use App\Kernel\Http\Strategies\DependancyResolverStrategy;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;

final class RouterServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id) : bool
    {
        return $id === Router::class;
    }

    public function register() : void
    {
        $container = $this->container;

        $container->addShared(Router::class, function () use ($container) {
            $router = new Router();
            require ROOT_DIR . '/app/routes.php';
            $strategy = new DependancyResolverStrategy();
            $strategy->setContainer($container);
            $router->setStrategy($strategy);
            $router->lazyMiddlewares(
                $this->middlewares()
            );
            return $router;
        });
    }

    /**
     * @return class-string[]
     */
    private function middlewares() : array
    {
        return [
            DatabaseMiddleware::class,
            ExceptionHandlerMiddleware::class,
        ];
    }
}
