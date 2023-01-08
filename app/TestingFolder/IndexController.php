<?php

declare(strict_types=1);

namespace App\TestingFolder;

use App\Kernel\Http\Controllers\HtmlController;
use Psr\Http\Message\ResponseInterface;

final class IndexController extends HtmlController
{
    public function index() : ResponseInterface
    {
        return $this->render("App.html.twig");
    }
}
