<?php

declare(strict_types=1);

namespace App\TestingDomain;

use App\Kernel\Http\Controllers\ViewController;
use Psr\Http\Message\ResponseInterface;

final class IndexController extends ViewController
{
    public function index() : ResponseInterface
    {
        return $this->render("App.html.twig");
    }
}
