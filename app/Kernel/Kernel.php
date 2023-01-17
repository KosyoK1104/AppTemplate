<?php

declare(strict_types=1);

namespace App\Kernel;

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Kernel
{
    private static ContainerInterface $container;

    private readonly ServerRequestInterface $serverRequest;
    private readonly Router $router;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    private function __construct()
    {
        $this->serverRequest = self::$container->get(ServerRequestInterface::class);
        $this->router = self::$container->get(Router::class);
    }

    /** @noinspection MagicMethodsValidityInspection */
    public static function __setContainer(ContainerInterface $container) : void
    {
        self::$container = $container;
    }

    public static function create() : self
    {
        return new self();
    }

    public function run() : void
    {
        $this->emit(
            $this->router
                ->dispatch(
                    $this->serverRequest
                )
        );
    }

    private function emit(ResponseInterface $response) : void
    {
        (new SapiEmitter())->emit($response);
    }
}
