<?php

declare(strict_types=1);

namespace App\Kernel\Http\Response;

use App\Kernel\Exceptions\ProvidesResponseCode;
use Psr\Http\Message\ResponseInterface;

final class ApiResponseFactory extends ResponseFactory
{
    private const HEADER_CONTENT_TYPE = 'application/json';

    public function unauthorized() : ResponseInterface
    {
        return parent::unauthorized()->withHeader('Content-type', self::HEADER_CONTENT_TYPE);
    }

    public function error(string $error, $code = ProvidesResponseCode::HTTP_BAD_REQUEST) : ResponseInterface
    {
        return parent::error($error, $code)->withHeader('Content-type', self::HEADER_CONTENT_TYPE);
    }

    public function success(string $data = '', $code = ProvidesResponseCode::HTTP_OK) : ResponseInterface
    {
        return parent::success($data, $code)->withHeader('Content-type', self::HEADER_CONTENT_TYPE);
    }
}
