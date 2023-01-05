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

    public function unauthorized(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(403);

        return $response->withBody($this->streamFactory->createStream('Unauthorized'));
    }

    public function error(string $error, $code = 400): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code);

        return $response->withBody($this->streamFactory->createStream($error));
    }


    public function success(string $data, $code = 200): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code);

        return $response->withBody($this->streamFactory->createStream($data));
    }
}
