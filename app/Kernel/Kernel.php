<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Exceptions\AppException;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Http\Exception as HttpException;
use League\Route\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Throwable;

final class Kernel
{
    private static ContainerInterface $container;
    private static ServerRequestInterface $serverRequest;
    private static ResponseInterface $response;
    private static StreamFactoryInterface $streamFactory;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    private function __construct()
    {
        self::$response = self::$container->get(ResponseInterface::class);
        self::$serverRequest = self::$container->get(ServerRequestInterface::class);
        self::$streamFactory = self::$container->get(StreamFactoryInterface::class);
    }

    public static function __setContainer(ContainerInterface $container) : void
    {
        Kernel::$container = $container;
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
            $router = Kernel::$container->get(Router::class);

            self::$response = $router->dispatch(Kernel::$serverRequest);
        }
        catch (HttpException $e) {
            self::$response = $this->getWithHeader($e->getStatusCode(), $e->getStatusCode(), $e->getMessage());
        }
        catch (AppException $e) {
            self::$response = $this->getWithHeader(400, 400, $e->getMessage());
        }
        catch (Throwable $e) {
            self::$response = $this->getWithHeader(500, 500, $e->getMessage());
        }

        $this->emit(self::$response);
    }

    protected function encodeError(int $code, string $message) : string|false
    {
        return json_encode(
            [
                'error' => [
                    'reason' => $message,
                    'code'   => $code,
                ],
                'code'  => $code,
            ]
        );
    }

    protected function getWithHeader(int $statusCode, int $code, string $message) : ResponseInterface
    {
        return self::$response
            ->withStatus($statusCode)
            ->withBody(
                self::$streamFactory
                    ->createStream(
                        $this->encodeError($code, $message)
                    )
            )
            ->withHeader('Content-type', 'application/json')
        ;
    }

    private function emit(ResponseInterface $response) : void
    {
        (new SapiEmitter())->emit($response);
    }
}
