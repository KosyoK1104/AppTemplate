<?php

declare(strict_types=1);

namespace App\Kernel\Http\Handlers;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ExceptionHandlerInterface
{
    public function handle(Throwable $e, ResponseInterface $response) : ResponseInterface;
}
