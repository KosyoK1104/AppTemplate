<?php

declare(strict_types=1);

namespace App\Kernel\Http\Request;

use Psr\Http\Message\ServerRequestInterface;

final class Request
{
    use ServerRequestParameterBag;

    public function __construct(
        private readonly ServerRequestInterface $serverRequest
    ) {
    }
}
