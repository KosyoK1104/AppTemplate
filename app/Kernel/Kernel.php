<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Http\Handlers\ExceptionHandler;
use App\Kernel\Http\Handlers\ExceptionHandlerInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
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
    private readonly ExceptionHandlerInterface $exceptionHandler;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    private function __construct()
    {
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
        try{
            $response = $this->router->dispatch($this->serverRequest);
        } catch (\Throwable $e){
            $response = $this->exceptionHandler->handle($e);
        }

        $this->emit(
            $response
        );
    }

    private function emit(ResponseInterface $response) : void
    {
        (new SapiStreamEmitter(1024))->emit($response);
    }
}
