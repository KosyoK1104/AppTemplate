<?php

declare(strict_types=1);

namespace App\Kernel\Http\Middlewares;

use App\Kernel\Http\Handlers\ExceptionHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ExceptionHandlerInterface $exceptionHandler
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            return $handler->handle($request);
        }
        catch (Throwable $e) {
            return $this->exceptionHandler->handle($e);
        }
    }
}
