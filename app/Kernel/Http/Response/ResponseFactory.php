<?php

declare(strict_types=1);

namespace App\Kernel\Http\Response;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class ResponseFactory
{
    public function __construct(
        protected readonly ResponseFactoryInterface $responseFactory,
        protected readonly StreamFactoryInterface $streamFactory
    ) {
    }

    public function unauthorized() : ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(403)
            ->withBody(
                $this->streamFactory
                    ->createStream('Unauthorized')
            )
        ;
    }

    public function error(string $error, int $code = 400) : ResponseInterface
    {
        return $this->responseFactory
            ->createResponse($code)
            ->withBody(
                $this->streamFactory
                    ->createStream($error)
            )
        ;
    }

    public function success(string $data, int $code = 200) : ResponseInterface
    {
        return $this->responseFactory
            ->createResponse($code)
            ->withBody(
                $this->streamFactory
                    ->createStream($data)
            )
        ;
    }
}
