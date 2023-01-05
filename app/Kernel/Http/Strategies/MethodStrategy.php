<?php

declare(strict_types=1);

namespace App\Kernel\Http\Strategies;

use App\Kernel\Http\Controllers\Controller;
use App\Kernel\Http\Request\Request;
use League\Route\Route;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

final class MethodStrategy extends ApplicationStrategy
{

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request) : ResponseInterface
    {
        if (!empty($route->getVars())) {
            foreach ($route->getVars() as $key => $value) {
                $request = $request->withAttribute($key, $value);
            }
        }

        $controller = $route->getCallable($this->getContainer());
        assert($controller[0] instanceof Controller);
        $reflection = new ReflectionMethod($controller[0], $controller[1]);

        $dependencies = $reflection->getParameters();
        $parameters = [];
        foreach ($dependencies as $key => $dependency) {
            if ($dependency instanceof ServerRequestInterface) {
                $parameters[$key] = $request;
                continue;
            }
            $parameters[$key] = $this->getContainer()->get($dependency->getType()->getName());
        }
        /**
         * @var Controller $controller[0]
         */
        $controller[0]->__setRequest(new Request($request));
        $response = $controller(...array_values($parameters));
        if (!$response instanceof ResponseInterface) {
            throw new RuntimeException('Controller must return ResponseInterface');
        }
        return $response;
    }
}
