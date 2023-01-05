<?php

declare(strict_types=1);

namespace App\ServiceProviders;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class HttpServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id) : bool
    {
        $provides = [
            ResponseFactoryInterface::class,
            StreamFactoryInterface::class,
            ServerRequestInterface::class,
            ResponseInterface::class,
        ];

        return in_array($id, $provides, true);
    }

    public function register() : void
    {
        $container = $this->getContainer();
        $container->addShared(ResponseFactoryInterface::class, fn() => new ResponseFactory());
        $container->addShared(StreamFactoryInterface::class, fn() => new StreamFactory());
        $container->addShared(ServerRequestInterface::class, fn() => ServerRequestFactory::fromGlobals());
        $container->addShared(ResponseInterface::class, fn() => new Response());
    }
}
