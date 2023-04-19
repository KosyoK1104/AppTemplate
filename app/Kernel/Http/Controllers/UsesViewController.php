<?php

declare(strict_types=1);

namespace App\Kernel\Http\Controllers;

use App\Kernel\Http\Response\ViewResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

trait UsesViewController
{
    private readonly Environment $twig;
    private readonly ViewResponseFactory $viewResponseFactory;

    /** @noinspection MagicMethodsValidityInspection */
    public function __setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    /** @noinspection MagicMethodsValidityInspection */
    public function __setViewResponseFactory(ViewResponseFactory $viewResponseFactory): void
    {
        $this->viewResponseFactory = $viewResponseFactory;
    }

    public function render(string $template, array $data = []): ResponseInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $template = $this->twig->load($template);

        return $this->viewResponseFactory
            ->success(
                $template
                    ->render($data)
            );
    }

}
