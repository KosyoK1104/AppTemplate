<?php

declare(strict_types=1);

namespace App\Kernel\Http\Middlewares;

use App\Shared\ContainerAware\ContainerAwareInterface;
use App\Shared\ContainerAware\ContainerAwareTrait;
use App\Shared\Database\Database;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class DatabaseMiddleware implements MiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if (!$this->container->has(Database::class)) {
            return $handler->handle($request);
        }
        $database = $this->container->get(Database::class);
        assert($database instanceof Database);

        if(!$database->isInTransaction()){
            return $handler->handle($request);
        }
        try {
            $response = $handler->handle($request);
            $database->commit();
        }
        catch (Throwable $e) {
            $database->rollback();
            throw $e;
        }
        return $response;
    }
}
