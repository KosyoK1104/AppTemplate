<?php

declare(strict_types=1);

namespace App\Kernel\Http\Handlers;

use App\Configuration\Config;
use App\Kernel\Exceptions\AppException;
use Laminas\Diactoros\ResponseFactory;
use League\Route\Http\Exception as HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Throwable;
use Whoops\Run as Whoops;

final class ExceptionHandler implements ExceptionHandlerInterface
{

    private ResponseInterface $response;

    public function __construct(
        private readonly Config $config,
        private readonly Whoops $whoops,
        private readonly StreamFactoryInterface $streamFactory
    ) {
        $this->response = (new ResponseFactory())->createResponse();
    }

    public function handle(Throwable $e) : ResponseInterface
    {
        if ($this->config->enviroment->isLocal()) {
            return $this->debugResponse($e);
        }
        if ($e instanceof HttpException) {
            return $this->getWithHeader($e->getStatusCode(), $e->getMessage());
        }
        if ($e instanceof AppException) {
            return $this->getWithHeader(400, $e->getMessage());
        }
        return $this->getWithHeader(500, 'Unexpected error!');
    }

    private function debugResponse(Throwable $e) : ResponseInterface
    {
        return $this->response
            ->withBody(
                $this->streamFactory
                    ->createStream(
                        $this->whoops->handleException($e)
                    )
            )
        ;
    }

    private function encodeError(int $code, string $message) : string|false
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

    private function getWithHeader(int $statusCode, string $message) : ResponseInterface
    {
        return $this->response
            ->withStatus($statusCode)
            ->withBody(
                $this->streamFactory
                    ->createStream(
                        $this->encodeError($statusCode, $message)
                    )
            )
            ->withHeader('Content-type', 'application/json')
        ;
    }
}
