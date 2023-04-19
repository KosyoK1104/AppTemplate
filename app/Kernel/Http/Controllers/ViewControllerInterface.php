<?php

declare(strict_types=1);

namespace App\Kernel\Http\Controllers;

use App\Kernel\Http\Response\ViewResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

interface ViewControllerInterface
{
    public function __setTwig(Environment $twig) : void;

    public function __setViewResponseFactory(ViewResponseFactory $responseFactory) : void;

    public function render(string $template, array $data = []) : ResponseInterface;
}
