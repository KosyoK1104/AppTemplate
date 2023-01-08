<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Http\Handlers\ExceptionHandler;
use App\Kernel\Http\Handlers\ExceptionHandlerInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final class Kernel
{
    private static ContainerInterface $container;
    private static ServerRequestInterface $serverRequest;
    private static ResponseInterface $response;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    private function __construct()
    {
        self::$response = self::$container->get(ResponseInterface::class);
        self::$serverRequest = self::$container->get(ServerRequestInterface::class);
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
        try {
            /**
             * @var Router $router
             */
            $router = self::$container->get(Router::class);

            self::$response = $router->dispatch(self::$serverRequest);
        }
        catch (Throwable $e) {
            /**
             * @var ExceptionHandler $exceptionHandler
             */
            $exceptionHandler = self::$container->get(ExceptionHandlerInterface::class);
            self::$response = $exceptionHandler->handle($e, self::$response);
        }

        $this->emit(self::$response);
    }

    private function emit(ResponseInterface $response) : void
    {
        (new SapiEmitter())->emit($response);
    }
}
