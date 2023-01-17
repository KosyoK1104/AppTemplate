<?php

declare(strict_types=1);

namespace App\Kernel\Http\Controllers;

use App\Kernel\Http\Response\ViewResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

abstract class ViewController extends Controller
{
    private readonly Environment $twig;
    private readonly ViewResponseFactory $responseFactory;

    /** @noinspection MagicMethodsValidityInspection */
    public function __setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    /** @noinspection MagicMethodsValidityInspection */
    public function __setResponseFactory(ViewResponseFactory $responseFactory): void
    {
        $this->responseFactory = $responseFactory;
    }

    public function render(string $template, array $data = []): ResponseInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $template = $this->twig->load($template);

        return $this->responseFactory
            ->success(
                $template
                    ->render($data)
            );
    }

}