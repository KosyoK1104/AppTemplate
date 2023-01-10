<?php

declare(strict_types=1);

namespace App\Kernel\Http\Response;

use Psr\Http\Message\ResponseInterface;

final class HtmlResponseFactory extends ResponseFactory
{
    private const HEADER_CONTENT_TYPE = 'text/html';

    public function unauthorized() : ResponseInterface
    {
        return parent::unauthorized()->withHeader('Content-type', self::HEADER_CONTENT_TYPE);
    }

    public function error(string $error, $code = 400) : ResponseInterface
    {
        return parent::error($error, $code)->withHeader('Content-type', self::HEADER_CONTENT_TYPE);
    }

    public function success(string $data, $code = 200) : ResponseInterface
    {
        return parent::success($data, $code)->withHeader('Content-type', self::HEADER_CONTENT_TYPE);
    }
}
