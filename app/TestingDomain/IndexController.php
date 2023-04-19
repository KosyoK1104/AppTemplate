<?php

declare(strict_types=1);

namespace App\TestingDomain;

use App\Kernel\Http\Controllers\Controller;
use App\Kernel\Http\Controllers\UsesViewController;
use App\Kernel\Http\Controllers\ViewControllerInterface;
use Psr\Http\Message\ResponseInterface;

final class IndexController extends Controller implements ViewControllerInterface
{
    use UsesViewController;
    public function index() : ResponseInterface
    {
        return $this->render("App.html.twig");
    }
}
