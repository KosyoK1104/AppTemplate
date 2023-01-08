<?php

declare(strict_types=1);

namespace App\Kernel\Http\Handlers;

use App\Configuration\Config;
use App\Kernel\Exceptions\AppException;
use League\Route\Http\Exception as HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Throwable;
use Whoops\Run as Whoops;

final class ExceptionHandler implements ExceptionHandlerInterface
{

    public function __construct(
        private readonly Config $config,
        private readonly Whoops $whoops,
        private readonly StreamFactoryInterface $streamFactory
    ) {
    }

    public
    function handle(
        Throwable $e,
        ResponseInterface $response
    ) : ResponseInterface {
        if ($this->config->enviroment->isLocal()) {
            return $response->withBody($this->streamFactory->createStream($this->whoops->handleException($e)));
        }

        if ($e instanceof HttpException) {
            return $this->getWithHeader($response, $e->getStatusCode(), $e->getStatusCode(), $e->getMessage());
        }
        if ($e instanceof AppException) {
            return $this->getWithHeader($response, 400, 400, $e->getMessage());
        }
        return $this->getWithHeader($response, 500, 500, $e->getMessage());
    }

    private
    function encodeError(
        int $code,
        string $message
    ) : string|false {
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

    private
    function getWithHeader(
        ResponseInterface $response,
        int $statusCode,
        int $code,
        string $message
    ) : ResponseInterface {
        return $response
            ->withStatus($statusCode)
            ->withBody(
                $this->streamFactory
                    ->createStream(
                        $this->encodeError($code, $message)
                    )
            )
            ->withHeader('Content-type', 'application/json')
        ;
    }
}
