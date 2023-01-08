<?php

declare(strict_types=1);

namespace App\Kernel;

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

    private ResponseInterface $response;
    private readonly ServerRequestInterface $serverRequest;
    private readonly Router $router;
    private readonly ExceptionHandlerInterface $exceptionHandler;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    private function __construct()
    {
        $this->response = self::$container->get(ResponseInterface::class);
        $this->serverRequest = self::$container->get(ServerRequestInterface::class);
        $this->router = self::$container->get(Router::class);
        $this->exceptionHandler = self::$container->get(ExceptionHandlerInterface::class);
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
            $this->response = $this->router->dispatch($this->serverRequest);
        }
        catch (Throwable $e) {
            $this->response = $this->exceptionHandler->handle($e, $this->response);
        }

        $this->emit($this->response);
    }

    private function emit(ResponseInterface $response) : void
    {
        (new SapiEmitter())->emit($response);
    }
}
