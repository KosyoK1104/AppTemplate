<?php

declare(strict_types=1);

namespace App\Kernel\Http\Middlewares;

use App\Shared\Database\Database;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class DatabaseMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    /**
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            $response = $handler->handle($request);
            $this->database->commit();
        }
        catch (Throwable $e) {
            $this->database->rollback();
            throw $e;
        }
        return $response;
    }
}
