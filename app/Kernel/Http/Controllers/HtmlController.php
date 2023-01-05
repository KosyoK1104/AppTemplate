<?php

declare(strict_types=1);

namespace App\Kernel\Http\Controllers;

use App\Kernel\Http\Response\HtmlResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class HtmlController extends Controller
{
    private readonly Environment $twig;
    private readonly HtmlResponseFactory $responseFactory;

    public function __setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    public function __setResponseFactory(HtmlResponseFactory $responseFactory): void
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(string $template, array $data = []): ResponseInterface
    {
        $template = $this->twig->load($template);

        return $this->responseFactory
            ->success(
                $template
                    ->render($data)
            );
    }
}
