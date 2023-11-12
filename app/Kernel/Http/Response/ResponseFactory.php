<?php

declare(strict_types=1);

namespace App\Kernel\Http\Response;

use App\Kernel\Exceptions\ProvidesResponseCode;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

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
            ->createResponse(ProvidesResponseCode::HTTP_UNAUTHORIZED)
            ->withBody(
                $this->streamFactory
                    ->createStream('Unauthorized')
            )
        ;
    }

    public function error(string $error, int $code = ProvidesResponseCode::HTTP_BAD_REQUEST) : ResponseInterface
    {
        return $this->responseFactory
            ->createResponse($code)
            ->withBody(
                $this->streamFactory
                    ->createStream($error)
            )
        ;
    }

    public function success(string $data, int $code = ProvidesResponseCode::HTTP_OK) : ResponseInterface
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
